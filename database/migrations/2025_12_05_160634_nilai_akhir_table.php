<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration: create_nilai_akhir_table.php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilai_akhir', function (Blueprint $table) {
            $table->char('id_nilai', 36)->primary();
            $table->char('id_pengajuan', 36);
            $table->char('id_dosen', 36);
            $table->char('nilai_huruf', 2); // A, A-, B+, B, dst
            $table->decimal('nilai_angka', 4, 2); // 0.00 - 4.00
            $table->text('penilaian_mitra')->nullable();
            $table->text('evaluasi_laporan')->nullable();
            $table->text('penilaian_dosen')->nullable();
            $table->dateTime('tanggal_penetapan');
            $table->string('periode', 20); // Gasal 2024/2025
            $table->timestamps();

            $table->foreign('id_pengajuan')
                ->references('id_pengajuan')->on('pengajuan_magang')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('id_dosen')
                ->references('id_dosen')->on('dosen_pembimbing')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_akhir');
    }
};