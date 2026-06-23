<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = ['pelajaran_id', 'siswa_id', 'tanggal', 'status'];

    // Relasi ke Pelajaran
    public function pelajaran()
    {
        return $this->belongsTo(Pelajaran::class, 'pelajaran_id');
    }

    // Relasi ke Siswa (User)
    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }
}
