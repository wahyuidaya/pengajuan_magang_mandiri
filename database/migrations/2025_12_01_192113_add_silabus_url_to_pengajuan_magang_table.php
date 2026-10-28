<?php

   use Illuminate\Database\Migrations\Migration;
   use Illuminate\Database\Schema\Blueprint;
   use Illuminate\Support\Facades\Schema;

   return new class extends Migration
   {
       public function up(): void
       {
           Schema::table('pengajuan_magang', function (Blueprint $table) {
               // ✅ Ganti after('laporan_akhir_url') jadi after('transkrip_url')
               $table->string('silabus_url', 255)->nullable()->after('transkrip_url');
           });
       }

       public function down(): void
       {
           Schema::table('pengajuan_magang', function (Blueprint $table) {
               $table->dropColumn('silabus_url');
           });
       }
   };