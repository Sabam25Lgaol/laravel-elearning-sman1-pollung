<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\User;

class KelasController extends Controller
{
    // Menampilkan halaman Data Kelas
    public function index()
    {
        // Mengambil semua data kelas dan mengurutkannya berdasarkan nama (misal: 10 IPA 1, 10 IPA 2)
        $kelas = Kelas::orderBy('nama_kelas', 'asc')->get();
        return view('admin.kelas', compact('kelas'));
    }

    // Menyimpan Kelas Baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|unique:kelas,nama_kelas|max:255',
        ], [
            'nama_kelas.unique' => 'Nama kelas ini sudah terdaftar di sistem!',
        ]);

        Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'deskripsi' => $request->deskripsi,
        ]);

        return back()->with('success', 'Data Kelas berhasil ditambahkan!');
    }

    // Mengupdate Kelas (Edit)
    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
            // Validasi agar nama kelas tidak boleh sama dengan kelas lain (kecuali dengan dirinya sendiri)
            'nama_kelas' => 'required|max:255|unique:kelas,nama_kelas,' . $kelas->id,
        ]);

        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
            'deskripsi' => $request->deskripsi,
        ]);

        return back()->with('success', 'Data Kelas berhasil diperbarui!');
    }

    // Menghapus Kelas
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return back()->with('success', 'Data Kelas berhasil dihapus!');
    }

    // Menampilkan daftar siswa berdasarkan nama kelas
    public function lihatSiswa($nama_kelas)
    {
        // Cari kelasnya untuk memastikan kelas itu ada
        $kelas = Kelas::where('nama_kelas', $nama_kelas)->firstOrFail();

        // Cari semua pengguna yang jabatannya 'Siswa' DAN berada di kelas tersebut
        $siswaDiKelas = User::role('Siswa')
                            ->where('kelas', $nama_kelas)
                            ->orderBy('name', 'asc')
                            ->get();

        return view('admin.kelas_siswa', compact('kelas', 'siswaDiKelas'));
    }
}
