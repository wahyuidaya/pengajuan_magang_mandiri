<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konversi_sks', function (Blueprint $table) {
            $table->uuid('id_konversi')->primary();

            $table->uuid('id_pengajuan');
            $table->uuid('id_mhs');

            $table->string('kode_mk', 20);
            $table->string('nama_mk', 100);
            $table->integer('sks');

            $table->enum('status', ['draft', 'disetujui'])->default('draft');
            $table->uuid('disetujui_oleh')->nullable();
            $table->timestamp('tanggal_disetujui')->nullable();

            $table->timestamps();

            // Foreign key
            $table->foreign('id_pengajuan')
                ->references('id_pengajuan')
                ->on('pengajuan_magang')
                ->onDelete('cascade');

            $table->foreign('id_mhs')
                ->references('id_mhs')
                ->on('mahasiswa')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konversi_sks');
    }
};