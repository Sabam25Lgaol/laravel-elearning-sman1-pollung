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
        Schema::create('tugas', function (Blueprint $table) {
            $table->id();
            $table->string('judul_tugas');
            $table->text('deskripsi')->nullable();
            $table->string('file_tugas')->nullable(); // File soal dari guru (opsional)
            $table->dateTime('tenggat_waktu'); // Batas waktu pengumpulan (Deadline)

            // Relasi ke tabel pelajarans
            $table->foreignId('pelajaran_id')->constrained('pelajarans')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};
