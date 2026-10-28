<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_magang', function (Blueprint $table) {
            // Tambah kolom untuk dosen pembimbing
            $table->unsignedBigInteger('id_dosen')->nullable()->after('id_mitra');
            
            // Tambah kolom untuk laporan
            $table->string('laporan_mingguan_url', 255)->nullable()->after('transkrip_url');
            $table->string('laporan_akhir_url', 255)->nullable()->after('laporan_mingguan_url');
            
            // Tambah kolom untuk nilai
            $table->string('nilai_mitra', 5)->nullable()->after('catatan_verifikator');
            $table->string('nilai_akhir', 5)->nullable()->after('nilai_mitra');
            
            // Foreign key ke dosen
            $table->foreign('id_dosen')
                ->references('id')->on('dosen_pembimbing')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_magang', function (Blueprint $table) {
            $table->dropForeign(['id_dosen']);
            $table->dropColumn([
                'id_dosen',
                'laporan_mingguan_url',
                'laporan_akhir_url',
                'nilai_mitra',
                'nilai_akhir'
            ]);
        });
    }
};