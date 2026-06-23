<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilUjian extends Model
{
    use HasFactory;
    protected $fillable = ['ujian_id', 'siswa_id', 'jumlah_benar', 'nilai', 'waktu_mulai_mengerjakan', 'waktu_selesai_mengerjakan', 'status'];

    public function ujian() { return $this->belongsTo(Ujian::class, 'ujian_id'); }
    public function siswa() { return $this->belongsTo(User::class, 'siswa_id'); }
}
