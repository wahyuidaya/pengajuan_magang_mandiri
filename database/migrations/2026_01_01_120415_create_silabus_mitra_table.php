<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('silabus_mitra', function (Blueprint $table) {
            $table->uuid('id_silabus')->primary();

            // relasi inti
            $table->uuid('id_pengajuan');
            $table->uuid('id_mitra');

            // data silabus
            $table->string('judul_silabus');
            $table->string('file_silabus'); // path file
            $table->text('deskripsi')->nullable();

            // status internal
            $table->enum('status', ['aktif', 'diganti'])->default('aktif');

            // waktu upload
            $table->timestamp('tanggal_upload')->useCurrent();

            // foreign key
            $table->foreign('id_pengajuan')
                  ->references('id_pengajuan')
                  ->on('pengajuan_magang')
                  ->onDelete('cascade');

            $table->foreign('id_mitra')
                  ->references('id_mitra')
                  ->on('mitra_perusahaan')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('silabus_mitra');
    }
};
