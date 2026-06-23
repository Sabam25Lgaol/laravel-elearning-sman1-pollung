<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelajaran;
use App\Models\Ujian;
use App\Models\SoalUjian;
use App\Models\HasilUjian;
use App\Models\JawabanUjian;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreUjianRequest;
use App\Http\Requests\UpdateUjianRequest;
use App\Http\Requests\StoreSoalRequest; // Import satpam soal baru

class UjianController extends Controller
{
    // =========================================================================
    // FUNGSI BARU: Untuk menampilkan tabel daftar kelas di menu "Ujian Online"
    // =========================================================================
    public function pilihKelas()
    {
        // Mengambil daftar mata pelajaran yang diajar oleh guru yang sedang login
        $pelajarans = Pelajaran::with('dataKelas')
                        ->where('guru_id', Auth::id())
                        ->get();

        // Mengarahkan ke halaman tabel daftar kelas khusus ujian
        return view('guru.ujian_pilih_kelas', compact('pelajarans'));
    }

    // =========================================================================
    // FUNGSI LAMA: Menampilkan daftar ujian setelah kelas dipilih
    // =========================================================================
    public function index($pelajaran_id)
    {
        $pelajaran = Pelajaran::findOrFail($pelajaran_id);
        if ($pelajaran->guru_id != Auth::id()) {
            abort(403, 'Akses Ditolak! Anda bukan pengampu kelas ini.');
        }

        $ujians = Ujian::where('pelajaran_id', $pelajaran_id)->orderBy('created_at', 'desc')->get();
        return view('guru.ujian', compact('pelajaran', 'ujians'));
    }

    public function store(StoreUjianRequest $request, $pelajaran_id)
    {
        Ujian::create([
            'pelajaran_id' => $pelajaran_id,
            'judul_ujian' => $request->judul_ujian,
            'deskripsi' => $request->deskripsi,
            'durasi' => $request->durasi,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
        ]);

        return back()->with('success', 'Wadah ujian berhasil dibuat! Silakan klik tombol "Kelola Soal".');
    }

    public function kelolaSoal($ujian_id)
    {
        $ujian = Ujian::with('pelajaran')->findOrFail($ujian_id);
        if ($ujian->pelajaran->guru_id != Auth::id()) {
            abort(403, 'Akses Ditolak!');
        }

        $soals = SoalUjian::where('ujian_id', $ujian_id)->get();
        return view('guru.ujian_soal', compact('ujian', 'soals'));
    }

    // BERHASIL DIRAMPINGKAN: Memanfaatkan StoreSoalRequest
    public function storeSoal(StoreSoalRequest $request, $ujian_id)
    {
        // Validasi, Authorization, dan Kondisi PG/Essay otomatis beres di pintu depan!

        $nama_gambar = null;
        if ($request->hasFile('gambar_soal')) {
            $file = $request->file('gambar_soal');
            $nama_gambar = time() . "_" . $file->getClientOriginalName();
            $file->move(public_path('uploads/soal'), $nama_gambar);
        }

        $kunci_jawaban_string = null;
        if ($request->jenis_soal == 'pilihan_ganda') {
            $kunci_jawaban_string = implode(',', $request->kunci_jawaban);
        }

        SoalUjian::create([
            'ujian_id' => $ujian_id,
            'jenis_soal' => $request->jenis_soal,
            'pertanyaan' => $request->pertanyaan,
            'gambar_soal' => $nama_gambar,
            'pilihan_a' => $request->pilihan_a,
            'pilihan_b' => $request->pilihan_b,
            'pilihan_c' => $request->pilihan_c,
            'pilihan_d' => $request->pilihan_d,
            'kunci_jawaban' => $kunci_jawaban_string,
        ]);

        return back()->with('success', 'Soal berhasil ditambahkan!');
    }

    public function update(UpdateUjianRequest $request, $id)
    {
        $ujian = Ujian::findOrFail($id);

        $ujian->update([
            'judul_ujian' => $request->judul_ujian,
            'deskripsi' => $request->deskripsi,
            'durasi' => $request->durasi,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
        ]);

        return back()->with('success', 'Jadwal dan detail ujian berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $ujian = Ujian::with('pelajaran')->findOrFail($id);
        if ($ujian->pelajaran->guru_id != Auth::id()) {
            abort(403, 'Akses Ditolak!');
        }

        $soals = SoalUjian::where('ujian_id', $id)->get();
        foreach($soals as $soal){
            if($soal->gambar_soal){
                $path = public_path('uploads/soal/' . $soal->gambar_soal);
                if (file_exists($path)) { unlink($path); }
            }
        }
        $ujian->delete();
        return back()->with('success', 'Ujian beserta seluruh soalnya berhasil dihapus!');
    }

    public function destroySoal($id)
    {
        $soal = SoalUjian::with('ujian.pelajaran')->findOrFail($id);
        if ($soal->ujian->pelajaran->guru_id != Auth::id()) {
            abort(403, 'Akses Ditolak!');
        }

        if ($soal->gambar_soal) {
            $path = public_path('uploads/soal/' . $soal->gambar_soal);
            if (file_exists($path)) { unlink($path); }
        }
        $soal->delete();
        return back()->with('success', 'Soal berhasil dihapus!');
    }

    // 1. Menampilkan Daftar Nilai Siswa di suatu ujian
    public function daftarNilai($ujian_id)
    {
        $ujian = Ujian::with('pelajaran.siswa')->findOrFail($ujian_id);
        if ($ujian->pelajaran->guru_id != Auth::id()) {
            abort(403, 'Akses Ditolak!');
        }

        $siswas = $ujian->pelajaran->siswa()->orderBy('name', 'asc')->get();
        $hasil_ujians = HasilUjian::where('ujian_id', $ujian_id)->get()->keyBy('siswa_id');

        return view('guru.ujian_nilai', compact('ujian', 'siswas', 'hasil_ujians'));
    }

    // 2. PERBAIKAN TEMUAN AUDIT: Suntik Eager Loading 'pelajaran' untuk mencegah N+1 Query
    public function koreksiJawaban($ujian_id, $siswa_id)
    {
        $ujian = Ujian::with('pelajaran')->findOrFail($ujian_id);
        if ($ujian->pelajaran->guru_id != Auth::id()) {
            abort(403, 'Akses Ditolak!');
        }

        $siswa = User::findOrFail($siswa_id);

        $soals = SoalUjian::where('ujian_id', $ujian_id)->get();
        $jawabans = JawabanUjian::where('ujian_id', $ujian_id)
                                ->where('siswa_id', $siswa_id)
                                ->get()
                                ->keyBy('soal_ujian_id');

        if ($jawabans->isEmpty()) {
            return back()->with('error', 'Siswa ini belum mengerjakan ujian!');
        }

        return view('guru.ujian_koreksi', compact('ujian', 'siswa', 'soals', 'jawabans'));
    }

    // 3. PERBAIKAN TEMUAN AUDIT: Suntik Eager Loading 'pelajaran' untuk mencegah N+1 Query
    public function simpanNilaiEssay(Request $request, $ujian_id, $siswa_id)
    {
        $ujian = Ujian::with('pelajaran')->findOrFail($ujian_id);
        if ($ujian->pelajaran->guru_id != Auth::id()) {
            abort(403, 'Akses Ditolak!');
        }

        $skor_essay = $request->skor;

        if($skor_essay) {
            foreach($skor_essay as $soal_id => $skor) {
                JawabanUjian::where('ujian_id', $ujian_id)
                            ->where('siswa_id', $siswa_id)
                            ->where('soal_ujian_id', $soal_id)
                            ->update(['skor' => $skor]);
            }
        }

        $semua_jawaban = JawabanUjian::where('ujian_id', $ujian_id)
                                     ->where('siswa_id', $siswa_id)
                                     ->get();

        $total_soal = SoalUjian::where('ujian_id', $ujian_id)->count();

        if($total_soal > 0) {
            $total_skor = $semua_jawaban->sum('skor');
            $nilai_akhir = $total_skor / $total_soal;

            HasilUjian::updateOrCreate(
                ['ujian_id' => $ujian_id, 'siswa_id' => $siswa_id],
                ['nilai' => $nilai_akhir]
            );
        }

        return redirect()->route('guru.ujian.nilai', $ujian_id)->with('success', 'Nilai Essay berhasil disimpan dan Nilai Akhir telah diperbarui!');
    }
}
