<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_status_pengajuan', function (Blueprint $table) {
            $table->char('id_log', 36)->primary();
            $table->char('id_pengajuan', 36);
            $table->string('status_dari', 30)->nullable();
            $table->string('status_ke', 30)->nullable();
            $table->timestamp('timestamp')->useCurrent();
            $table->char('pelaku', 36);
            $table->text('catatan')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('id_pengajuan')
                ->references('id_pengajuan')->on('pengajuan_magang')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('pelaku')
                ->references('id_user')->on('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_status_pengajuan');
    }
};