<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    use HasFactory;
    protected $fillable = ['pelajaran_id', 'judul_ujian', 'deskripsi', 'durasi', 'waktu_mulai', 'waktu_selesai', 'acak_soal', 'acak_jawaban'];

    protected $casts = [
        'acak_soal' => 'boolean',
        'acak_jawaban' => 'boolean',
    ];

    public function pelajaran() { return $this->belongsTo(Pelajaran::class, 'pelajaran_id'); }
    public function soal() { return $this->hasMany(SoalUjian::class, 'ujian_id'); }
    public function hasil() { return $this->hasMany(HasilUjian::class, 'ujian_id'); }
}
