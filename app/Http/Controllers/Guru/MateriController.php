<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelajaran;
use App\Models\Materi;
use Illuminate\Support\Facades\Auth;
// IMPORT SATPAM BARU KITA
use App\Http\Requests\StoreMateriRequest;
use App\Http\Requests\UpdateMateriRequest;

class MateriController extends Controller
{
    // =========================================================================
    // FUNGSI BARU: Untuk menampilkan tabel daftar kelas di menu "Materi"
    // =========================================================================
    public function pilihKelas()
    {
        // Mengambil daftar mata pelajaran yang diajar oleh guru yang sedang login
        $pelajarans = Pelajaran::with('dataKelas')
                        ->where('guru_id', Auth::id())
                        ->get();

        // Mengarahkan ke halaman tabel daftar kelas yang baru saja kita buat
        return view('guru.materi_pilih_kelas', compact('pelajarans'));
    }

    // =========================================================================
    // FUNGSI LAMA: Menampilkan detail materi setelah kelas dipilih
    // =========================================================================
    public function index($pelajaran_id)
    {
        $pelajaran = Pelajaran::findOrFail($pelajaran_id);

        if ($pelajaran->guru_id != Auth::id()) {
            abort(403, 'Akses Ditolak! Ini bukan kelas Anda.');
        }

        $materis = Materi::where('pelajaran_id', $pelajaran_id)->latest()->get();
        return view('guru.materi', compact('pelajaran', 'materis'));
    }

    // Menggunakan Form Request: StoreMateriRequest
    public function store(StoreMateriRequest $request, $pelajaran_id)
    {
        // Validasi dan Authorization beres di pintu depan

        $nama_file = null;
        if ($request->hasFile('file_materi')) {
            $file = $request->file('file_materi');
            $nama_file = time() . "_" . $file->getClientOriginalName();
            $file->move(public_path('uploads/materi'), $nama_file);
        }

        Materi::create([
            'judul_materi' => $request->judul_materi,
            'isi_materi' => $request->isi_materi,
            'file_materi' => $nama_file,
            'link_youtube' => $request->link_youtube,
            'pelajaran_id' => $pelajaran_id,
        ]);

        return back()->with('success', 'Materi baru berhasil ditambahkan!');
    }

    // Menggunakan Form Request: UpdateMateriRequest
    public function update(UpdateMateriRequest $request, $id)
    {
        // Validasi dan Authorization beres di pintu depan
        $materi = Materi::findOrFail($id);

        $data = [
            'judul_materi' => $request->judul_materi,
            'isi_materi' => $request->isi_materi,
            'link_youtube' => $request->link_youtube,
        ];

        if ($request->hasFile('file_materi')) {
            $pathLama = public_path('uploads/materi/' . $materi->file_materi);
            if (file_exists($pathLama) && $materi->file_materi) {
                unlink($pathLama);
            }

            $file = $request->file('file_materi');
            $nama_file = time() . "_" . $file->getClientOriginalName();
            $file->move(public_path('uploads/materi'), $nama_file);

            $data['file_materi'] = $nama_file;
        }

        $materi->update($data);

        return back()->with('success', 'Materi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $materi = Materi::with('pelajaran')->findOrFail($id);

        // AUTHORIZATION: Pastikan yang menghapus adalah pembuatnya!
        if ($materi->pelajaran->guru_id != Auth::id()) {
            abort(403, 'Akses Ditolak! Anda tidak berhak menghapus materi ini.');
        }

        $path = public_path('uploads/materi/' . $materi->file_materi);
        if (file_exists($path) && $materi->file_materi) {
            unlink($path);
        }

        $materi->delete();

        return back()->with('success', 'Materi berhasil dihapus!');
    }
}
