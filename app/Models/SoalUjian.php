<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalUjian extends Model
{
    use HasFactory;

    // Mengganti $fillable menjadi $guarded agar kolom jenis_soal dan gambar_soal bisa masuk
    protected $guarded = [];

    // Relasi ke tabel Ujian (Milik Ujian apa soal ini?)
    public function ujian()
    {
        return $this->belongsTo(Ujian::class, 'ujian_id');
    }

    /**
     * Relasi ke tabel Jawaban Ujian
     * Hubungan: 1 Soal memiliki BANYAK Jawaban (dari berbagai siswa)
     */
    public function jawaban()
    {
        // PERBAIKAN BUG: Mengubah 'soal_id' menjadi 'soal_ujian_id' agar sinkron dengan database!
        return $this->hasMany(JawabanUjian::class, 'soal_ujian_id');
    }
}
