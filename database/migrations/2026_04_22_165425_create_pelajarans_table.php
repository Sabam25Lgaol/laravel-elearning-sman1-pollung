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
       Schema::create('pelajarans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelajaran'); // Contoh: Matematika, Fisika, Biologi
            $table->text('deskripsi')->nullable(); // Keterangan pelajaran (Boleh kosong)

            // Mengaitkan pelajaran ini dengan Guru (mengambil ID dari tabel users)
            // 'cascade' artinya kalau guru dihapus, mata pelajarannya ikut terhapus
            $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelajarans');
    }
};
