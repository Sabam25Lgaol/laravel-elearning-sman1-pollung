<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ujians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelajaran_id')->constrained('pelajarans')->onDelete('cascade');
            $table->string('judul_ujian'); // Contoh: Ujian Tengah Semester
            $table->text('deskripsi')->nullable();
            $table->integer('durasi'); // Durasi dalam satuan menit (contoh: 60)
            $table->dateTime('waktu_mulai')->nullable(); // Kapan ujian dibuka
            $table->dateTime('waktu_selesai')->nullable(); // Kapan ujian ditutup
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ujians');
    }
};
