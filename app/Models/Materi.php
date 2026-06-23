<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi
    protected $fillable = [
        'judul_materi',
        'isi_materi',
        'file_materi',
        'link_youtube',
        'pelajaran_id'
    ];

    // Relasi: Satu materi ini MILIK SATU pelajaran
    public function pelajaran()
    {
        return $this->belongsTo(Pelajaran::class, 'pelajaran_id');
    }
}
