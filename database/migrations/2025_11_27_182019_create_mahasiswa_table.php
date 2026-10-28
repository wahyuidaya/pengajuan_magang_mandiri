<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->char('id_mhs', 36)->primary();
            $table->char('id_user', 36);
            $table->string('npm', 12)->unique();
            $table->string('nama_lengkap', 25);
            $table->string('no_hp', 15)->nullable();
            $table->boolean('aktif')->default(1);
            $table->decimal('ipk', 3, 2)->nullable();
            $table->integer('sks_lulus')->nullable();
            $table->string('prodi', 50)->default('Sistem Informasi');
            $table->string('foto_url', 100)->nullable();

            $table->foreign('id_user')
                ->references('id_user')->on('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};