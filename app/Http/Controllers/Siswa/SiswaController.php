<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelajaran;
use App\Models\Materi;
use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use Illuminate\Support\Facades\Auth;
use App\Models\Ujian;
use App\Models\SoalUjian;
use App\Models\HasilUjian;
use App\Models\JawabanUjian;
use App\Http\Requests\SubmitTugasRequest;
use App\Http\Requests\SubmitUjianRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // PERBAIKAN TEMUAN 5: Import fasad Log
use Carbon\Carbon;

class SiswaController extends Controller
{
    public function dashboard()
    {
        $pelajarans = Auth::user()->pelajaran()->with('guru')->get();
        return view('siswa.dashboard', compact('pelajarans'));
    }

    public function materi($pelajaran_id)
    {
        $pelajaran = Pelajaran::with(['guru', 'dataKelas'])->findOrFail($pelajaran_id);
        $materis = Materi::where('pelajaran_id', $pelajaran_id)->get();
        return view('siswa.materi', compact('pelajaran', 'materis'));
    }

    public function tugas($pelajaran_id)
    {
        $pelajaran = Pelajaran::with(['guru', 'dataKelas'])->findOrFail($pelajaran_id);
        $tugas = Tugas::where('pelajaran_id', $pelajaran_id)->get();
        $pengumpulan = PengumpulanTugas::where('siswa_id', Auth::id())->get()->keyBy('tugas_id');

        return view('siswa.tugas', compact('pelajaran', 'tugas', 'pengumpulan'));
    }

    // PERBAIKAN TEMUAN 2: Proteksi Otorisasi Akses Tugas Sisi Server
    public function submitTugas(SubmitTugasRequest $request, $tugas_id)
    {
        $tugas = Tugas::findOrFail($tugas_id);

        // SUNTIKAN PROTEKSI TEMUAN 2: Cek apakah siswa terdaftar di pelajaran tugas ini
        $terdaftar = Auth::user()->pelajaran()->where('pelajaran_id', $tugas->pelajaran_id)->exists();
        if (!$terdaftar) {
            abort(403, 'Akses ditolak! Anda tidak terdaftar pada pelajaran ini.');
        }

        if (Carbon::now()->gt($tugas->tenggat_waktu)) {
            return back()->with('error', 'Gagal! Batas waktu pengumpulan tugas sudah habis.');
        }

        $pengumpulanLama = PengumpulanTugas::where('tugas_id', $tugas_id)
                                            ->where('siswa_id', Auth::id())
                                            ->first();

        if ($pengumpulanLama && $pengumpulanLama->nilai !== null) {
            return back()->with('error', 'Gagal! Tugas ini sudah dinilai oleh guru dan tidak dapat diubah.');
        }

        $file = $request->file('file_jawaban');
        $nama_file = time() . "_jawaban_" . Auth::id() . "_" . $file->getClientOriginalName();

        if ($pengumpulanLama && $pengumpulanLama->file_jawaban) {
            $pathLama = public_path('uploads/jawaban/' . $pengumpulanLama->file_jawaban);
            if (file_exists($pathLama)) {
                unlink($pathLama);
            }
        }

        $file->move(public_path('uploads/jawaban'), $nama_file);

        // Pengaman tingkat DB berupa Unique Index harus dipasang di migration (Lihat Langkah 2)
        PengumpulanTugas::updateOrCreate(
            ['tugas_id' => $tugas_id, 'siswa_id' => Auth::id()],
            [
                'file_jawaban' => $nama_file,
                'catatan_siswa' => $request->catatan_siswa,
            ]
        );

        return back()->with('success', 'Luar biasa! Jawaban tugas berhasil dikumpulkan.');
    }

    public function absensi($pelajaran_id)
    {
        $pelajaran = Pelajaran::with(['guru', 'dataKelas'])->findOrFail($pelajaran_id);
        $absensis = \App\Models\Absensi::where('pelajaran_id', $pelajaran_id)
                                       ->where('siswa_id', Auth::id())
                                       ->orderBy('tanggal', 'desc')
                                       ->get();

        $rekap = [
            'Hadir' => $absensis->where('status', 'Hadir')->count(),
            'Izin' => $absensis->where('status', 'Izin')->count(),
            'Sakit' => $absensis->where('status', 'Sakit')->count(),
            'Alfa' => $absensis->where('status', 'Alfa')->count(),
        ];

        return view('siswa.absensi', compact('pelajaran', 'absensis', 'rekap'));
    }

    public function ujian($pelajaran_id)
    {
        $pelajaran = Pelajaran::with(['guru', 'dataKelas'])->findOrFail($pelajaran_id);
        $ujians = Ujian::where('pelajaran_id', $pelajaran_id)->withCount('soal')->get();
        $hasil = HasilUjian::where('siswa_id', Auth::id())->get()->keyBy('ujian_id');

        return view('siswa.ujian', compact('pelajaran', 'ujians', 'hasil'));
    }

    // PERBAIKAN TEMUAN 1: Proteksi Masuk Ujian Berdasarkan Otorisasi Kelas
    public function kerjakanUjian($ujian_id)
    {
        $ujian = Ujian::with('soal')->findOrFail($ujian_id);
        $sekarang = Carbon::now();

        // SUNTIKAN PROTEKSI TEMUAN 1: Blokir jika siswa mencoba tembak URL ujian kelas lain
        $terdaftar = Auth::user()->pelajaran()->where('pelajaran_id', $ujian->pelajaran_id)->exists();
        if (!$terdaftar) {
            abort(403, 'Akses ditolak! Anda tidak terdaftar pada pelajaran ini.');
        }

        if ($sekarang->lt($ujian->waktu_mulai) || $sekarang->gt($ujian->waktu_selesai)) {
            return redirect()->route('siswa.ujian', $ujian->pelajaran_id)->with('error', 'Akses ditolak! Ujian belum dimulai atau waktunya sudah habis.');
        }

        $cek_hasil = HasilUjian::where('ujian_id', $ujian_id)->where('siswa_id', Auth::id())->first();
        if ($cek_hasil) {
            return redirect()->route('siswa.ujian', $ujian->pelajaran_id)->with('error', 'Anda sudah mengerjakan ujian ini!');
        }

        return view('siswa.kerjakan_ujian', compact('ujian'));
    }

    // PERBAIKAN TEMUAN 1, 4 & 5: Pengamanan Proses Simpan Jawaban
    public function submitUjian(SubmitUjianRequest $request, $ujian_id)
    {
        $ujian = Ujian::with('soal')->findOrFail($ujian_id);
        $sekarang = Carbon::now();

        // SUNTIKAN PROTEKSI TEMUAN 1: Blokir request jika token siswa tidak terdaftar di kelas ini
        $terdaftar = Auth::user()->pelajaran()->where('pelajaran_id', $ujian->pelajaran_id)->exists();
        if (!$terdaftar) {
            abort(403, 'Akses ditolak! Anda tidak terdaftar pada pelajaran ini.');
        }

        if ($sekarang->gt(Carbon::parse($ujian->waktu_selesai)->addMinutes(2))) {
            return redirect()->route('siswa.ujian', $ujian->pelajaran_id)->with('error', 'Gagal mengumpulkan! Waktu ujian ini telah berakhir.');
        }

        $cek_hasil = HasilUjian::where('ujian_id', $ujian_id)->where('siswa_id', Auth::id())->first();
        if ($cek_hasil) {
            return redirect()->route('siswa.ujian', $ujian->pelajaran_id)->with('error', 'Anda sudah mengirimkan jawaban untuk ujian ini sebelumnya.');
        }

        $jawaban_siswa = $request->jawaban;
        $total_soal = $ujian->soal->count();
        if ($total_soal == 0) {
            return back()->with('error', 'Soal belum tersedia, hubungi guru!');
        }

        DB::beginTransaction();

        try {
            $total_skor = 0;
            $ada_essay = false;

            foreach ($ujian->soal as $soal) {
                $jawab = isset($jawaban_siswa[$soal->id]) ? $jawaban_siswa[$soal->id] : null;
                $jawaban_teks = null;
                $skor = 0;

                if ($soal->jenis_soal == 'pilihan_ganda') {
                    if (is_array($jawab)) {
                        sort($jawab);
                        $jawaban_teks = implode(',', $jawab);
                    } else {
                        $jawaban_teks = $jawab;
                    }

                    $kunci_tersimpan = explode(',', $soal->kunci_jawaban);
                    sort($kunci_tersimpan);
                    $kunci_terurut = implode(',', $kunci_tersimpan);

                    if ($jawaban_teks == $kunci_terurut) {
                        $skor = 100;
                    }
                    $total_skor += $skor;

                } else {
                    $ada_essay = true;
                    $jawaban_teks = $jawab;
                    $skor = 0;
                }

                JawabanUjian::create([
                    'ujian_id' => $ujian_id,
                    'soal_ujian_id' => $soal->id,
                    'siswa_id' => Auth::id(),
                    'jawaban_teks' => $jawaban_teks,
                    'skor' => $skor
                ]);
            }

            $nilai_akhir = $total_skor / $total_soal;

            HasilUjian::create([
                'ujian_id' => $ujian_id,
                'siswa_id' => Auth::id(),
                'jumlah_benar' => 0,
                'nilai' => $nilai_akhir,
                'waktu_mulai_mengerjakan' => null, // PERBAIKAN TEMUAN 4: Set null karena tidak menggunakan tracking session mulai ujian
                'status' => 'Selesai'
            ]);

            DB::commit();

            $pesan = $ada_essay
                ? 'Ujian selesai! Nilai Pilihan Ganda sudah terekam. Nilai Essay menunggu koreksi Guru.'
                : 'Ujian selesai! Nilai akhir Anda: ' . round($nilai_akhir);

            return redirect()->route('siswa.ujian', $ujian->pelajaran_id)->with('success', $pesan);

        } catch (\Exception $e) {
            DB::rollBack();

            // PERBAIKAN TEMUAN 5: Tulis log error sistem secara spesifik untuk mempermudah debugging
            Log::error('Gagal memproses transaksi submit ujian siswa.', [
                'siswa_id'  => Auth::id(),
                'ujian_id'  => $ujian_id,
                'error_msg' => $e->getMessage(),
                'line'      => $e->getLine()
            ]);

            return redirect()->route('siswa.ujian', $ujian->pelajaran_id)->with('error', 'Terjadi kesalahan sistem saat menyimpan jawaban. Silakan coba lagi.');
        }
    }
}
