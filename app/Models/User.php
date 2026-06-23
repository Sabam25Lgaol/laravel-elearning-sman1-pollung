<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // Import trait Spatie untuk RBAC
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;

// TAMBAHKAN 'nomor_induk' dan 'kelas' DI BARIS INI:
#[Fillable(['name', 'email', 'password', 'google_id', 'avatar', 'nomor_induk', 'kelas'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles; // Tambahkan HasRoles di sini

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke model Kelas
     * Hubungan: Siswa dimiliki oleh/berada di dalam 1 Kelas (Belongs To)
     */
    public function dataKelas()
    {
        // Kita pakai nama fungsi 'dataKelas' karena 'kelas' sudah dipakai sebagai nama kolom.
        // Format: belongsTo(ModelTujuan, 'kolom_foreign_key_di_user', 'kolom_primary_key_di_kelas')
        return $this->belongsTo(Kelas::class, 'kelas', 'nama_kelas');
    }

    // Relasi Enrollment (1 Siswa bisa masuk ke banyak Pelajaran)
    public function pelajaran()
    {
        return $this->belongsToMany(Pelajaran::class, 'pelajaran_siswa', 'siswa_id', 'pelajaran_id');
    }
}
