<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_magang', function (Blueprint $table) {
            $table->char('id_dosen', 36)->nullable()->after('id_mitra');
            
            $table->foreign('id_dosen')
                ->references('id_dosen')->on('dosen_pembimbing')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_magang', function (Blueprint $table) {
            $table->dropForeign(['id_dosen']);
            $table->dropColumn('id_dosen');
        });
    }
};