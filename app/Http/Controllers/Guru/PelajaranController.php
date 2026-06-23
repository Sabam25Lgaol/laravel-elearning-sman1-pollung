<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelajaran;
use Illuminate\Support\Facades\Auth;

class PelajaranController extends Controller
{
    // 1. Menampilkan halaman daftar pelajaran khusus untuk Guru
    public function index()
    {
        // Ambil pelajaran yang HANYA ditugaskan oleh Admin ke Guru yang sedang login
        $pelajarans = Pelajaran::where('guru_id', Auth::id())->orderBy('nama_pelajaran', 'asc')->get();

        return view('guru.pelajaran', compact('pelajarans'));
    }

    // ==========================================
    // 2. INI YANG BARU: Masuk ke Ruang Kelas Spesifik
    // ==========================================
    public function show($id)
    {
        $pelajaran = Pelajaran::findOrFail($id);

        // Keamanan Tingkat Dewa: Pastikan kelas ini benar-benar milik guru yang sedang login!
        if ($pelajaran->guru_id != Auth::id()) {
            abort(403, 'Akses Ditolak! Anda tidak memiliki izin untuk masuk ke kelas ini.');
        }

        return view('guru.ruang_kelas', compact('pelajaran'));
    }

    // 3. Fungsi simpan (Saat ini sudah diambil alih oleh Admin)
    // Kita biarkan kodenya di sini agar sistem routing web.php tidak error.
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelajaran' => 'required',
            'deskripsi' => 'nullable'
        ]);

        Pelajaran::create([
            'nama_pelajaran' => $request->nama_pelajaran,
            'deskripsi' => $request->deskripsi,
            'guru_id' => Auth::id(),
        ]);

        return back()->with('success', 'Mata Pelajaran berhasil ditambahkan!');
    }
}
