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
        Schema::table('soal_ujians', function (Blueprint $table) {
            // 1. Tambah kolom untuk jenis soal dan gambar
            $table->enum('jenis_soal', ['pilihan_ganda', 'essay'])->default('pilihan_ganda')->after('ujian_id');
            $table->string('gambar_soal')->nullable()->after('pertanyaan');

            // 2. Ubah kolom pilihan dan kunci jawaban menjadi Boleh Kosong (Nullable) untuk soal Essay
            $table->string('pilihan_a')->nullable()->change();
            $table->string('pilihan_b')->nullable()->change();
            $table->string('pilihan_c')->nullable()->change();
            $table->string('pilihan_d')->nullable()->change();
            $table->string('kunci_jawaban')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soal_ujians', function (Blueprint $table) {
            // Rollback jika terjadi kesalahan
            $table->dropColumn(['jenis_soal', 'gambar_soal']);

            $table->string('pilihan_a')->nullable(false)->change();
            $table->string('pilihan_b')->nullable(false)->change();
            $table->string('pilihan_c')->nullable(false)->change();
            $table->string('pilihan_d')->nullable(false)->change();
            $table->string('kunci_jawaban')->nullable(false)->change();
        });
    }
};
