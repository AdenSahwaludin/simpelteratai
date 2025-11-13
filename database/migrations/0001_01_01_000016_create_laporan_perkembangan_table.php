<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 /**
  * Run the migrations.
  */
 public function up(): void
 {
  Schema::create('laporan_perkembangan', function (Blueprint $table) {
   $table->string('id_laporan', 3)->primary();
   $table->string('id_siswa', 4);
   $table->string('id_mata_pelajaran', 3);
   $table->integer('nilai');
   $table->string('id_absensi', 4);
   $table->text('komentar')->nullable();
   $table->timestamps();

   $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('cascade');
   $table->foreign('id_mata_pelajaran')->references('id_mata_pelajaran')->on('mata_pelajaran')->onDelete('cascade');
   $table->foreign('id_absensi')->references('id_absensi')->on('absensi')->onDelete('cascade');
  });
 }

 /**
  * Reverse the migrations.
  */
 public function down(): void
 {
  Schema::dropIfExists('laporan_perkembangan');
 }
};
