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
        Schema::create('hasil_ujians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ujian_id')->constrained('ujians')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');
            $table->integer('jumlah_benar')->nullable();
            $table->integer('nilai')->nullable(); // Nilai akhir (0-100)

            // PERBAIKAN: Dibuat nullable() karena menyesuaikan Controller terbaru
            $table->dateTime('waktu_mulai_mengerjakan')->nullable();

            $table->dateTime('waktu_selesai_mengerjakan')->nullable();
            $table->enum('status', ['Mengerjakan', 'Selesai'])->default('Mengerjakan');
            $table->timestamps();

            // SUNTIKAN PROTEKSI: Mencegah Race Condition (Double Submit) di level Database
            $table->unique(['ujian_id', 'siswa_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_ujians');
    }
};
