<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mitra_perusahaan', function (Blueprint $table) {
            $table->char('id_mitra', 36)->primary();
            $table->char('id_user', 36);
            $table->string('nama_perusahaan', 50);
            $table->text('alamat')->nullable();
            $table->string('kontak_pic', 100)->nullable();
            $table->string('no_telp_pic', 15)->nullable();
            $table->string('email_pic', 100)->nullable();
            $table->enum('status_verifikasi', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');

            $table->foreign('id_user')
                ->references('id_user')->on('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mitra_perusahaan');
    }
};