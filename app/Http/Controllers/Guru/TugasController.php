<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelajaran;
use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use Illuminate\Support\Facades\Auth;
// IMPORT KETIGA SATPAM KITA:
use App\Http\Requests\StoreTugasRequest;
use App\Http\Requests\UpdateTugasRequest;
use App\Http\Requests\NilaiTugasRequest; // Import satpam untuk nilai

class TugasController extends Controller
{
    // =========================================================================
    // FUNGSI BARU: Untuk menampilkan tabel daftar kelas di menu "Tugas Siswa"
    // =========================================================================
    public function pilihKelas()
    {
        // Mengambil daftar mata pelajaran yang diajar oleh guru yang sedang login
        $pelajarans = Pelajaran::with('dataKelas')
                        ->where('guru_id', Auth::id())
                        ->get();

        // Mengarahkan ke halaman tabel daftar kelas khusus tugas
        return view('guru.tugas_pilih_kelas', compact('pelajarans'));
    }

    // =========================================================================
    // FUNGSI LAMA: Menampilkan halaman kelola tugas untuk suatu pelajaran
    // =========================================================================
    public function index($pelajaran_id)
    {
        $pelajaran = Pelajaran::findOrFail($pelajaran_id);

        // KEAMANAN: Pastikan yang buka halaman ini adalah Guru yang mengampu pelajaran tersebut
        if ($pelajaran->guru_id != Auth::id()) {
            abort(403, 'Akses Ditolak! Ini bukan kelas Anda.');
        }

        // Ambil tugas dan urutkan dari yang paling baru dibuat
        $tugas = Tugas::where('pelajaran_id', $pelajaran_id)->latest()->get();

        return view('guru.tugas', compact('pelajaran', 'tugas'));
    }

    // Menyimpan tugas baru ke database (MENGGUNAKAN FORM REQUEST)
    public function store(StoreTugasRequest $request, $pelajaran_id)
    {
        // Validasi & Authorization otomatis ditangani oleh StoreTugasRequest di pintu depan

        $nama_file = null;
        if ($request->hasFile('file_tugas')) {
            $file = $request->file('file_tugas');
            $nama_file = time() . "_" . $file->getClientOriginalName();
            $file->move(public_path('uploads/tugas'), $nama_file);
        }

        Tugas::create([
            'judul_tugas' => $request->judul_tugas,
            'deskripsi' => $request->deskripsi,
            'file_tugas' => $nama_file,
            'tenggat_waktu' => $request->tenggat_waktu,
            'pelajaran_id' => $pelajaran_id,
        ]);

        return back()->with('success', 'Tugas berhasil ditambahkan!');
    }

    // Menampilkan daftar jawaban siswa
    public function cekJawaban($tugas_id)
    {
        $tugas = Tugas::with('pelajaran')->findOrFail($tugas_id);

        // KEAMANAN: Pastikan Guru tidak bisa mengintip jawaban siswa di kelas guru lain
        if ($tugas->pelajaran->guru_id != Auth::id()) {
            abort(403, 'Akses Ditolak!');
        }

        // Ambil semua data pengumpulan beserta nama siswanya
        $pengumpulans = PengumpulanTugas::with('siswa')->where('tugas_id', $tugas_id)->get();

        return view('guru.tugas_jawaban', compact('tugas', 'pengumpulans'));
    }

    // Menyimpan nilai yang diberikan guru (MENGGUNAKAN FORM REQUEST BARU)
    public function nilaiTugas(NilaiTugasRequest $request, $pengumpulan_id)
    {
        // Validasi dan pengecekan otorisasi guru (authorization) otomatis lewat NilaiTugasRequest!

        $pengumpulan = PengumpulanTugas::findOrFail($pengumpulan_id);
        $pengumpulan->update([
            'nilai' => $request->nilai,
            'catatan_guru' => $request->catatan_guru,
        ]);

        return back()->with('success', 'Nilai berhasil disimpan!');
    }

    // Menyimpan perubahan (Edit) Tugas (MENGGUNAKAN FORM REQUEST)
    public function update(UpdateTugasRequest $request, $id)
    {
        // Validasi & Authorization otomatis ditangani oleh UpdateTugasRequest di pintu depan

        $tugas = Tugas::findOrFail($id);

        $data = [
            'judul_tugas' => $request->judul_tugas,
            'deskripsi' => $request->deskripsi,
            'tenggat_waktu' => $request->tenggat_waktu,
        ];

        // Jika guru meng-upload file soal baru
        if ($request->hasFile('file_tugas')) {
            // Hapus file lama
            $pathLama = public_path('uploads/tugas/' . $tugas->file_tugas);
            if (file_exists($pathLama) && $tugas->file_tugas) {
                unlink($pathLama);
            }

            // Upload file baru
            $file = $request->file('file_tugas');
            $nama_file = time() . "_" . $file->getClientOriginalName();
            $file->move(public_path('uploads/tugas'), $nama_file);

            $data['file_tugas'] = $nama_file;
        }

        $tugas->update($data);

        return back()->with('success', 'Detail tugas berhasil diperbarui!');
    }

    // Menghapus Tugas
    public function destroy($id)
    {
        $tugas = Tugas::with('pelajaran')->findOrFail($id);

        // AUTHORIZATION: Pastikan yang mau menghapus adalah guru pengampu!
        if ($tugas->pelajaran->guru_id != Auth::id()) {
            abort(403, 'Akses Ditolak! Anda tidak memiliki izin untuk menghapus tugas ini.');
        }

        // Hapus file soal dari folder
        $path = public_path('uploads/tugas/' . $tugas->file_tugas);
        if (file_exists($path) && $tugas->file_tugas) {
            unlink($path);
        }

        // Hapus data tugas dari database
        $tugas->delete();

        return back()->with('success', 'Tugas berhasil dihapus permanen!');
    }
}
