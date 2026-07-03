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
        if (!Schema::hasColumn('soal_ujians', 'jenis_soal')) {
            Schema::table('soal_ujians', function (Blueprint $table) {
                $table->enum('jenis_soal', ['pilihan_ganda', 'essay'])->default('pilihan_ganda')->after('ujian_id');
            });
        }

        if (!Schema::hasColumn('soal_ujians', 'gambar_soal')) {
            Schema::table('soal_ujians', function (Blueprint $table) {
                $table->string('gambar_soal')->nullable()->after('pertanyaan');
            });
        }

        Schema::table('soal_ujians', function (Blueprint $table) {
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
        if (Schema::hasColumn('soal_ujians', 'jenis_soal')) {
            Schema::table('soal_ujians', function (Blueprint $table) {
                $table->dropColumn('jenis_soal');
            });
        }

        if (Schema::hasColumn('soal_ujians', 'gambar_soal')) {
            Schema::table('soal_ujians', function (Blueprint $table) {
                $table->dropColumn('gambar_soal');
            });
        }

        Schema::table('soal_ujians', function (Blueprint $table) {
            $table->string('pilihan_a')->nullable(false)->change();
            $table->string('pilihan_b')->nullable(false)->change();
            $table->string('pilihan_c')->nullable(false)->change();
            $table->string('pilihan_d')->nullable(false)->change();
            $table->string('kunci_jawaban')->nullable(false)->change();
        });
    }
};
