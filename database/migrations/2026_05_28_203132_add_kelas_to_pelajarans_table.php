<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pelajarans', function (Blueprint $table) {
            // Pastikan baris ini ada dan tidak ada typo
            $table->string('kelas')->nullable()->after('guru_id');
        });
    }

    public function down(): void
    {
        Schema::table('pelajarans', function (Blueprint $table) {
            $table->dropColumn('kelas');
        });
    }
};
