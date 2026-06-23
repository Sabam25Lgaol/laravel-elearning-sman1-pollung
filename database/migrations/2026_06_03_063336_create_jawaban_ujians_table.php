<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jawaban_ujians', function (Blueprint $table) {
            $table->id();
            // Relasi ke ujian yang sedang dikerjakan
            $table->foreignId('ujian_id')->constrained('ujians')->onDelete('cascade');

            // Relasi ke soal yang spesifik
            $table->foreignId('soal_ujian_id')->constrained('soal_ujians')->onDelete('cascade');

            // Relasi ke siswa yang menjawab
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');

            // Tempat menyimpan teks essay atau pilihan 'A', 'B', 'C', 'D'
            $table->text('jawaban_teks')->nullable();

            // Skor untuk soal ini (otomatis terisi untuk PG, diisi manual untuk Essay)
            $table->integer('skor')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jawaban_ujians');
    }
};
