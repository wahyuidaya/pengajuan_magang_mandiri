<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration: create_laporan_akhir_table.php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_akhir', function (Blueprint $table) {
            $table->char('id_laporan_akhir', 36)->primary();
            $table->char('id_pengajuan', 36);
            $table->string('judul_laporan', 200);
            $table->text('deskripsi')->nullable();
            $table->string('file_path', 255);
            $table->dateTime('tanggal_upload');
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->text('catatan_reviewer')->nullable();
            $table->timestamps();

            $table->foreign('id_pengajuan')
                ->references('id_pengajuan')->on('pengajuan_magang')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_akhir');
    }
};