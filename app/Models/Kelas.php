<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    // Supaya Laravel tahu tabelnya bernama 'kelas' (bukan 'kelas_s' atau 'kelas')
    protected $table = 'kelas';

    // Mengizinkan pengisian kolom secara massal (Standar Laravel)
    protected $fillable = ['nama_kelas', 'deskripsi'];

    /**
     * Relasi ke model User (Siswa)
     * Hubungan: 1 Kelas memiliki BANYAK Siswa (One-to-Many)
     */
    public function siswa()
    {
        // Mencari User yang kolom 'kelas'-nya sama dengan 'nama_kelas' di tabel ini
        return $this->hasMany(User::class, 'kelas', 'nama_kelas');
    }

    /**
     * Relasi ke model Pelajaran
     * Hubungan: 1 Kelas memiliki BANYAK Mata Pelajaran (One-to-Many)
     */
    public function pelajaran()
    {
        // Mencari Pelajaran yang kolom 'kelas'-nya sama dengan 'nama_kelas' di tabel ini
        return $this->hasMany(Pelajaran::class, 'kelas', 'nama_kelas');
    }
}
