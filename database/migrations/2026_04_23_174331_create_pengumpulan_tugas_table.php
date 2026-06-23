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
        Schema::create('pengumpulan_tugas', function (Blueprint $table) {
            $table->id();
            $table->string('file_jawaban'); // File jawaban dari siswa
            $table->text('catatan_siswa')->nullable();
            $table->integer('nilai')->nullable(); // Nilai dari guru (0-100)
            $table->text('catatan_guru')->nullable(); // Komentar guru (opsional)

            // Relasi ke tabel tugas dan users (siswa)
            $table->foreignId('tugas_id')->constrained('tugas')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // SUNTIKAN PROTEKSI: Mencegah duplikasi submit tugas di level Database
            $table->unique(['tugas_id', 'siswa_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumpulan_tugas');
    }
};
