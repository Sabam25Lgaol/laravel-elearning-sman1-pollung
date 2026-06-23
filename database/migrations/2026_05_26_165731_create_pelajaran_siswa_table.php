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
        Schema::create('pelajaran_siswa', function (Blueprint $table) {
            $table->id();
            // Menyambungkan ke ID Pelajaran
            $table->foreignId('pelajaran_id')->constrained('pelajarans')->onDelete('cascade');
            // Menyambungkan ke ID Siswa (dari tabel users)
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelajaran_siswa');
    }
};
