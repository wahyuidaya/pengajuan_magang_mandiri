<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('laporan_mingguan', function (Blueprint $table) {
            $table->uuid('id_laporan_mingguan')->primary();
            $table->uuid('id_pengajuan');
            $table->string('bulan', 20);
            $table->string('judul_laporan');
            $table->text('deskripsi')->nullable();
            $table->string('file_path');
            $table->dateTime('tanggal_upload');
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->text('catatan_reviewer')->nullable();
            $table->timestamps();

            $table->foreign('id_pengajuan')
                ->references('id_pengajuan')
                ->on('pengajuan_magang')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('laporan_mingguan');
    }
};