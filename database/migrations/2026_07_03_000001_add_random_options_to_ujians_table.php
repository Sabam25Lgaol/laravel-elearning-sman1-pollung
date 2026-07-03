<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ujians', function (Blueprint $table) {
            $table->boolean('acak_soal')->default(false)->after('waktu_selesai');
            $table->boolean('acak_jawaban')->default(false)->after('acak_soal');
        });
    }

    public function down(): void
    {
        Schema::table('ujians', function (Blueprint $table) {
            $table->dropColumn(['acak_soal', 'acak_jawaban']);
        });
    }
};
