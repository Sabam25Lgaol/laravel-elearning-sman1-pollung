<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pelajaran;
use App\Models\Kelas;
use App\Http\Requests\StorePenggunaRequest; // <-- SUNTIKAN SATPAM (FORM REQUEST) UNTUK PENGGUNA BARU

class AdminController extends Controller
{
    // ==========================================
    // 0. DASHBOARD ADMIN
    // ==========================================
    public function dashboard()
    {
        // Menghitung jumlah pengguna berdasarkan jabatannya menggunakan fitur Spatie
        $total_admin = User::role('Admin')->count();
        $total_guru = User::role('Guru')->count();
        $total_siswa = User::role('Siswa')->count();

        // Mengambil semua data pengguna untuk ditampilkan di tabel dashboard (Eager Loading 'roles' sudah mantap!)
        $users = User::with('roles')->orderBy('name', 'asc')->get();

        return view('admin.dashboard', compact('total_admin', 'total_guru', 'total_siswa', 'users'));
    }

    // ==========================================
    // 1. FITUR KELOLA PENGGUNA
    // ==========================================
    public function pengguna()
    {
        // Ambil semua pengguna beserta jabatannya (Eager Loading 'roles' sudah mantap!)
        $users = User::with('roles')->get();

        // Ambil data kelas dari database, urutkan berdasarkan abjad
        $kelasList = Kelas::orderBy('nama_kelas', 'asc')->get();

        // Kirim data $users dan $kelasList ke tampilan halaman Pengguna
        return view('admin.pengguna', compact('users', 'kelasList'));
    }

    // Menambah Pengguna Baru dari Admin (MENGGUNAKAN FORM REQUEST)
    public function storePengguna(StorePenggunaRequest $request)
    {
        // Validasi sudah hilang dari sini karena diurus oleh StorePenggunaRequest di pintu depan!

        // Buat user baru. Login utama memakai OAuth Google, jadi password dibuat acak untuk keamanan.
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt(\Illuminate\Support\Str::random(32)),
            'nomor_induk' => $request->nomor_induk,
            // Logika cerdas: Kalau jabatannya bukan Siswa, kelas otomatis dikosongkan (null)
            'kelas' => $request->role === 'Siswa' ? $request->kelas : null,
        ]);

        // Berikan jabatan (role) via Spatie
        $user->assignRole($request->role);

        return back()->with('success', 'Pengguna baru berhasil ditambahkan!');
    }

    public function ubahRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $isSuperAdmin = $user->email == 'sabamgaol25@gmail.com';
        $roleSaatIni = $user->roles->pluck('name')->first();

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:Admin,Guru,Siswa',
            'nomor_induk' => 'required|string|max:255',
            'kelas' => 'required_if:role,Siswa|nullable|string|max:255',
        ]);

        $emailBerubah = $user->email !== $request->email;

        if ($isSuperAdmin && $request->role !== $roleSaatIni) {
            return back()->with('error', 'Jabatan Super Admin tidak boleh diubah!');
        }

        // Simpan NIS / NIP dan KELAS ke database
        $dataUpdate = [
            'email' => $request->email,
            'nomor_induk' => $request->nomor_induk,
            'kelas' => $request->role === 'Siswa' ? $request->kelas : null // Update agar selaras: Jika diubah ke Guru, hapus kelasnya
        ];

        if ($emailBerubah) {
            $dataUpdate['google_id'] = null;
            $dataUpdate['avatar'] = null;
        }

        $user->update($dataUpdate);

        // Ganti jabatan menggunakan fitur Spatie
        $user->syncRoles($request->role);
        return back()->with('success', 'Data & Jabatan pengguna berhasil diperbarui!');
    }

    // Menghapus Pengguna
    public function destroyPengguna($id)
    {
        $user = User::findOrFail($id);

        // Keamanan tingkat dewa: Cegah Admin menghapus dirinya sendiri saat sedang login
        // dan cegah penghapusan Super Admin (kamu)
        if ($user->email == 'sabamgaol25@gmail.com' || $user->id == auth()->id()) {
            return back()->with('error', 'Akses Ditolak! Anda tidak bisa menghapus Super Admin atau akun yang sedang Anda gunakan.');
        }

        $user->delete();

        return back()->with('success', 'Data pengguna berhasil dihapus secara permanen!');
    }

    // ==========================================
    // 2. FITUR KELOLA PELAJARAN (KURIKULUM)
    // ==========================================
    public function pelajaran()
    {
        // SUNTIKAN EAGER LOADING: Ambil semua pelajaran berserta guru pengajar dan data kelasnya sekaligus
        $pelajarans = Pelajaran::with(['guru', 'dataKelas'])->get();

        // Ambil HANYA pengguna yang menjabat sebagai Guru untuk ditaruh di pilihan (dropdown) form
        $gurus = User::role('Guru')->get();
        // Ambil data kelas untuk pilihan dropdown kelas
        $kelasList = Kelas::orderBy('nama_kelas', 'asc')->get();

        return view('admin.pelajaran', compact('pelajarans', 'gurus', 'kelasList'));
    }

    public function storePelajaran(Request $request)
    {
        $request->validate([
            'nama_pelajaran' => 'required',
            'guru_id' => 'required|exists:users,id',
            'kelas' => 'required' // Wajib pilih kelas
        ]);

        // 1. Simpan Pelajaran Baru ke Database
        $pelajaran = Pelajaran::create([
            'nama_pelajaran' => $request->nama_pelajaran,
            'deskripsi' => $request->deskripsi,
            'guru_id' => $request->guru_id,
            'kelas' => $request->kelas,
        ]);

        // ==========================================
        // MAGIC AUTO-ENROLLMENT SISWA! 🪄
        // ==========================================
        // 2. Cari semua anak yang punya role Siswa DAN berada di kelas tersebut
        $siswaDiKelas = User::role('Siswa')->where('kelas', $request->kelas)->pluck('id');

        // 3. Masukkan mereka semua sekaligus ke pelajaran ini!
        $pelajaran->siswa()->sync($siswaDiKelas);

        return back()->with('success', 'Mata Pelajaran berhasil ditambahkan dan Siswa kelas ' . $request->kelas . ' otomatis digabungkan!');
    }

    // Menampilkan halaman centang nama siswa untuk pelajaran tertentu (Bisa dipakai untuk cek manual)
    public function kelolaSiswaPelajaran($id)
    {
        // SUNTIKAN EAGER LOADING: Load relasi 'siswa' agar pluck() di bawahnya tidak memicu N+1 Query
        $pelajaran = \App\Models\Pelajaran::with('siswa')->findOrFail($id);

        $semuaSiswa = \App\Models\User::role('Siswa')->orderBy('name', 'asc')->get();

        // Ambil ID siswa yang SAAT INI sudah terdaftar di pelajaran tersebut
        $siswaTerdaftar = $pelajaran->siswa->pluck('id')->toArray();

        return view('admin.pelajaran_siswa', compact('pelajaran', 'semuaSiswa', 'siswaTerdaftar'));
    }

    // Menyimpan hasil centangan Admin ke database (Pivot Table)
    public function syncSiswaPelajaran(Request $request, $id)
    {
        $pelajaran = \App\Models\Pelajaran::findOrFail($id);

        // Fitur sync() otomatis memasukkan siswa yang dicentang dan menghapus yang tidak dicentang
        $pelajaran->siswa()->sync($request->siswa_ids);

        return redirect()->route('admin.pelajaran')->with('success', 'Daftar siswa untuk pelajaran ' . $pelajaran->nama_pelajaran . ' berhasil diatur!');
    }

    // ==========================================
    // EDIT & HAPUS PELAJARAN
    // ==========================================

    // Menyimpan perubahan Pelajaran (Edit Nama / Ganti Guru / Ganti Kelas)
    public function updatePelajaran(Request $request, $id)
    {
        $pelajaran = \App\Models\Pelajaran::findOrFail($id);

        $request->validate([
            'nama_pelajaran' => 'required',
            'guru_id' => 'required|exists:users,id',
            'kelas' => 'required'
        ]);

        // 1. Update Detail Pelajaran
        $pelajaran->update([
            'nama_pelajaran' => $request->nama_pelajaran,
            'deskripsi' => $request->deskripsi,
            'guru_id' => $request->guru_id,
            'kelas' => $request->kelas,
        ]);

        // 2. Auto-sync ulang jika kelasnya diganti
        $siswaDiKelas = User::role('Siswa')->where('kelas', $request->kelas)->pluck('id');
        $pelajaran->siswa()->sync($siswaDiKelas);

        return back()->with('success', 'Detail pelajaran diperbarui & data siswa otomatis disesuaikan dengan kelas ' . $request->kelas . '!');
    }

    // Menghapus Pelajaran secara permanen
    public function destroyPelajaran($id)
    {
        $pelajaran = \App\Models\Pelajaran::findOrFail($id);
        $pelajaran->delete(); // Ini otomatis akan menghapus data di pivot table (enrollment) juga

        return back()->with('success', 'Mata Pelajaran berhasil dihapus!');
    }
}
