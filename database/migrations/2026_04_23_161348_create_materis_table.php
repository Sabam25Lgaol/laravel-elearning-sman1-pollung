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
        Schema::create('materis', function (Blueprint $table) {
            $table->id();
            $table->string('judul_materi'); // Contoh: Bab 1 - Sejarah Linux
            $table->text('isi_materi')->nullable(); // Penjelasan teks panjang
            $table->string('file_materi')->nullable(); // Untuk menyimpan nama file PDF/Word yang diupload
            $table->string('link_youtube')->nullable(); // Kalau guru mau kasih video

            // Relasi: Materi ini milik pelajaran yang mana?
            // cascade = kalau mata pelajarannya dihapus, semua materinya ikut terhapus
            $table->foreignId('pelajaran_id')->constrained('pelajarans')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materis');
    }
};
