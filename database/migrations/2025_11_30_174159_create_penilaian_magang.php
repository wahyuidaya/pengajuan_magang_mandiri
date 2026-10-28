<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian_magang', function (Blueprint $table) {
            $table->char('id_penilaian', 36)->primary();
            $table->char('id_pengajuan', 36); // FK ke pengajuan_magang
            
            // Penilaian dari Mitra
            $table->string('file_penilaian')->nullable(); // Path file (Excel/PDF)
            $table->timestamp('tanggal_upload_mitra')->nullable();
            
            // Nilai Akhir dari Prodi
            $table->string('nilai_akhir', 3)->nullable(); // A, B, AB, A-, B+, dll
            $table->timestamp('tanggal_input_prodi')->nullable();
            
            $table->timestamps();
            
            $table->foreign('id_pengajuan')
                ->references('id_pengajuan')->on('pengajuan_magang')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian_magang');
    }
};