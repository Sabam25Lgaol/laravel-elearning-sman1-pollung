<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelajaran extends Model
{
    use HasFactory;

    // 1. Kolom yang diizinkan untuk diisi data
    protected $fillable = [
        'nama_pelajaran',
        'deskripsi',
        'guru_id',
        'kelas', // <--- INI DIA TAMBAHANNYA
    ];

    /**
     * Relasi ke model Kelas
     * Hubungan: Mata Pelajaran dimiliki oleh/berada di dalam 1 Kelas (Belongs To)
     */
    public function dataKelas()
    {
        // Kita pakai nama fungsi 'dataKelas' karena 'kelas' sudah dipakai sebagai nama kolom.
        // Format: belongsTo(ModelTujuan, 'kolom_foreign_key_di_pelajaran', 'kolom_primary_key_di_kelas')
        return $this->belongsTo(Kelas::class, 'kelas', 'nama_kelas');
    }

    // 2. Membuat Relasi: Satu Pelajaran dimiliki oleh Satu Guru
    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    // Relasi: Satu Pelajaran MEMILIKI BANYAK Materi
    public function materis()
    {
        return $this->hasMany(Materi::class, 'pelajaran_id');
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'pelajaran_id');
    }

    // Relasi untuk Moodle-like Enrollment (1 Pelajaran punya banyak Siswa)
    public function siswa()
    {
        return $this->belongsToMany(User::class, 'pelajaran_siswa', 'pelajaran_id', 'siswa_id');
    }
}
