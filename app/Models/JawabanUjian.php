<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanUjian extends Model
{
    use HasFactory;

    protected $guarded = []; // Buka semua gerbang pengisian data

    // Relasi balik ke siswa
    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    // Relasi balik ke soal ujian
    public function soal()
    {
        return $this->belongsTo(SoalUjian::class, 'soal_ujian_id');
    }
}
