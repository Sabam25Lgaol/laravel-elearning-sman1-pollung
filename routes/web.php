<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// =========================================================================
// 0. IMPORT CONTROLLER GLOBAL / AUTH
// =========================================================================
use App\Http\Controllers\GoogleController;

// =========================================================================
// 1. IMPORT CONTROLLER KELOMPOK ADMIN (Folder: app/Http/Controllers/Admin)
// =========================================================================
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\KelasController;

// =========================================================================
// 2. IMPORT CONTROLLER KELOMPOK GURU (Folder: app/Http/Controllers/Guru)
// =========================================================================
use App\Http\Controllers\Guru\GuruController;
use App\Http\Controllers\Guru\PelajaranController;
use App\Http\Controllers\Guru\MateriController;
use App\Http\Controllers\Guru\TugasController;
use App\Http\Controllers\Guru\AbsensiController;
use App\Http\Controllers\Guru\UjianController;

// =========================================================================
// 3. IMPORT CONTROLLER KELOMPOK SISWA (Folder: app/Http/Controllers/Siswa)
// =========================================================================
use App\Http\Controllers\Siswa\SiswaController;


// 1. HALAMAN AWAL
Route::get('/', function () {
    return view('welcome');
})->name('login');

// 2. ALUR LOGIN GOOGLE
Route::prefix('auth/google')->group(function () {
    Route::get('/redirect', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
    Route::get('/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');
});

// 3. PENGATUR LALU LINTAS DASHBOARD (Terminal Utama)
Route::get('/dashboard', function () {
    $user = Auth::user();

    // Kalau dia Admin, lempar ke ruangan Admin
    if ($user->hasRole('Admin')) {
        return redirect('/admin/dashboard');
    }
    // Kalau dia Guru, lempar ke ruangan Guru
    elseif ($user->hasRole('Guru')) {
        return redirect('/guru/dashboard');
    }
    // Kalau dia Siswa, lempar ke ruangan Siswa
    elseif ($user->hasRole('Siswa')) {
        return redirect('/siswa/dashboard');
    }

    return "Anda tidak memiliki akses ke sistem ini.";
})->middleware(['auth'])->name('dashboard');


// ==========================================
// 4. RUANGAN KHUSUS (DIKUNCI OLEH MIDDLEWARE)
// ==========================================

// Ruangan Admin (HANYA Admin yang boleh masuk)
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Rute Kelola Pengguna
    Route::get('/pengguna', [AdminController::class, 'pengguna'])->name('admin.pengguna');
    Route::post('/pengguna/{id}/role', [AdminController::class, 'ubahRole'])->name('admin.ubah_role');
    Route::post('/pengguna/store', [AdminController::class, 'storePengguna'])->name('admin.pengguna.store');
    Route::delete('/pengguna/{id}/delete', [AdminController::class, 'destroyPengguna'])->name('admin.pengguna.delete');

    // ------------------------------------------
    // RUTE MASTER DATA KELAS
    // ------------------------------------------
    Route::get('/kelas', [KelasController::class, 'index'])->name('admin.kelas');
    Route::post('/kelas/store', [KelasController::class, 'store'])->name('admin.kelas.store');
    Route::post('/kelas/{id}/update', [KelasController::class, 'update'])->name('admin.kelas.update');
    Route::delete('/kelas/{id}/delete', [KelasController::class, 'destroy'])->name('admin.kelas.delete');

    // Rute untuk melihat daftar siswa di dalam kelas tertentu
    Route::get('/kelas/{nama_kelas}/siswa', [KelasController::class, 'lihatSiswa'])->name('admin.kelas.siswa');

    // ------------------------------------------
    // RUTE KELOLA PELAJARAN
    // ------------------------------------------
    Route::get('/pelajaran', [AdminController::class, 'pelajaran'])->name('admin.pelajaran');
    Route::post('/pelajaran', [AdminController::class, 'storePelajaran'])->name('admin.pelajaran.store');
    Route::put('/pelajaran/{id}/update', [AdminController::class, 'updatePelajaran'])->name('admin.pelajaran.update');
    Route::delete('/pelajaran/{id}/delete', [AdminController::class, 'destroyPelajaran'])->name('admin.pelajaran.delete');

    // Rute Enrollment (Memasukkan Siswa ke Pelajaran)
    Route::get('/pelajaran/{id}/siswa', [AdminController::class, 'kelolaSiswaPelajaran'])->name('admin.pelajaran.siswa');
    Route::post('/pelajaran/{id}/siswa', [AdminController::class, 'syncSiswaPelajaran'])->name('admin.pelajaran.siswa.sync');
});

// Ruangan Guru (HANYA Guru yang boleh masuk)
Route::middleware(['auth', 'role:Guru'])->prefix('guru')->group(function () {

    Route::get('/dashboard', [GuruController::class, 'dashboard'])->name('guru.dashboard');

    // ==========================================
    // TAMBAHAN BARU: JALUR MENU SIDEBAR MATERI
    // ==========================================
    Route::get('/materi', [MateriController::class, 'pilihKelas'])->name('guru.materi.index');
    Route::get('/tugas', [TugasController::class, 'pilihKelas'])->name('guru.tugas.index');
    Route::get('/ujian', [UjianController::class, 'pilihKelas'])->name('guru.ujian.index');
    Route::get('/absensi', [AbsensiController::class, 'pilihKelas'])->name('guru.absensi.index');

    // ==========================================
    // RUTE PELAJARAN & RUANG KELAS GURU
    // ==========================================
    Route::get('/pelajaran', [PelajaranController::class, 'index'])->name('guru.pelajaran');
    Route::get('/pelajaran/{id}/ruang', [PelajaranController::class, 'show'])->name('guru.pelajaran.show');
    Route::post('/pelajaran', [PelajaranController::class, 'store'])->name('guru.pelajaran.store');

    Route::get('/pelajaran/{id}/materi', [MateriController::class, 'index'])->name('guru.materi');
    Route::post('/pelajaran/{id}/materi', [MateriController::class, 'store'])->name('guru.materi.store');

    // Rute Edit dan Hapus Materi
    Route::put('/materi/{id}/update', [MateriController::class, 'update'])->name('guru.materi.update');
    Route::delete('/materi/{id}/delete', [MateriController::class, 'destroy'])->name('guru.materi.delete');

    Route::get('/pelajaran/{id}/tugas', [TugasController::class, 'index'])->name('guru.tugas');
    Route::post('/pelajaran/{id}/tugas', [TugasController::class, 'store'])->name('guru.tugas.store');

    // Rute untuk mengecek jawaban dan memberi nilai
    Route::get('/tugas/{tugas_id}/jawaban', [TugasController::class, 'cekJawaban'])->name('guru.tugas.jawaban');
    Route::post('/pengumpulan/{pengumpulan_id}/nilai', [TugasController::class, 'nilaiTugas'])->name('guru.tugas.nilai');

    // Rute Edit dan Hapus Tugas
    Route::put('/tugas/{id}/update', [TugasController::class, 'update'])->name('guru.tugas.update');
    Route::delete('/tugas/{id}/delete', [TugasController::class, 'destroy'])->name('guru.tugas.delete');

    Route::get('/pelajaran/{id}/absensi', [AbsensiController::class, 'index'])->name('guru.absensi');
    Route::post('/pelajaran/{id}/absensi', [AbsensiController::class, 'store'])->name('guru.absensi.store');

    Route::get('/pelajaran/{id}/ujian', [UjianController::class, 'index'])->name('guru.ujian');
    Route::post('/pelajaran/{id}/ujian', [UjianController::class, 'store'])->name('guru.ujian.store');

    // Rute untuk mengelola soal ujian
    Route::get('/ujian/{id}/soal', [UjianController::class, 'kelolaSoal'])->name('guru.ujian.soal');
    Route::post('/ujian/{id}/soal', [UjianController::class, 'storeSoal'])->name('guru.ujian.soal.store');

    // Rute Edit dan Hapus Ujian & Soal
    Route::put('/ujian/{id}/update', [UjianController::class, 'update'])->name('guru.ujian.update');
    Route::delete('/ujian/{id}/delete', [UjianController::class, 'destroy'])->name('guru.ujian.delete');
    Route::delete('/soal/{id}/delete', [UjianController::class, 'destroySoal'])->name('guru.ujian.soal.delete');

    // ==========================================
    // TAMBAHAN BARU: RUTE PENILAIAN & KOREKSI ESSAY
    // ==========================================
    Route::get('/ujian/{ujian_id}/nilai', [UjianController::class, 'daftarNilai'])->name('guru.ujian.nilai');
    Route::get('/ujian/{ujian_id}/koreksi/{siswa_id}', [UjianController::class, 'koreksiJawaban'])->name('guru.ujian.koreksi');
    Route::post('/ujian/{ujian_id}/koreksi/{siswa_id}', [UjianController::class, 'simpanNilaiEssay'])->name('guru.ujian.simpan_nilai_essay');
});

// Ruangan Siswa (HANYA Siswa yang boleh masuk)
Route::middleware(['auth', 'role:Siswa'])->prefix('siswa')->group(function () {
    Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('siswa.dashboard');
    Route::get('/pelajaran/{id}/materi', [SiswaController::class, 'materi'])->name('siswa.materi');

    Route::get('/pelajaran/{id}/tugas', [SiswaController::class, 'tugas'])->name('siswa.tugas');
    Route::post('/tugas/{tugas_id}/submit', [SiswaController::class, 'submitTugas'])->name('siswa.tugas.submit');

    Route::get('/pelajaran/{id}/absensi', [SiswaController::class, 'absensi'])->name('siswa.absensi');

    // Rute Ujian Siswa
    Route::get('/pelajaran/{id}/ujian', [SiswaController::class, 'ujian'])->name('siswa.ujian');
    Route::get('/ujian/{id}/kerjakan', [SiswaController::class, 'kerjakanUjian'])->name('siswa.ujian.kerjakan');
    Route::post('/ujian/{id}/submit', [SiswaController::class, 'submitUjian'])->name('siswa.ujian.submit');
});
