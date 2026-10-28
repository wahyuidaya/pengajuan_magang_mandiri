<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pengajuan_magang')) {
            Schema::create('pengajuan_magang', function (Blueprint $table) {
                $table->char('id_pengajuan', 36)->primary();
                $table->char('id_mhs', 36);
                $table->char('id_mitra', 36)->nullable();
                $table->string('nama_perusahaan', 150);
                $table->text('alamat_perusahaan')->nullable();
                $table->string('posisi_dilamar', 100);
                $table->string('periode_magang', 50)->nullable();
                $table->string('semester', 20)->nullable();
                $table->string('surat_pengantar_mahasiswa', 255)->nullable();
                $table->string('krs_url', 255)->nullable();
                $table->string('transkrip_url', 255)->nullable();
                $table->string('laporan_mingguan_url', 255)->nullable();
                $table->string('laporan_akhir_url', 255)->nullable();
                $table->enum('status', ['draft', 'diajukan', 'verifikasi', 'revisi', 'disetujui', 'ditolak'])->default('diajukan');
                $table->timestamp('tanggal_pengajuan')->useCurrent();
                $table->text('catatan_verifikator')->nullable();
                $table->string('nilai_mitra', 10)->nullable();
                $table->string('nilai_akhir', 10)->nullable();
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

                $table->foreign('id_mhs')->references('id_mhs')->on('mahasiswa')->cascadeOnDelete()->cascadeOnUpdate();
                $table->foreign('id_mitra')->references('id_mitra')->on('mitra_perusahaan')->nullOnDelete()->cascadeOnUpdate();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_magang');
    }
};
