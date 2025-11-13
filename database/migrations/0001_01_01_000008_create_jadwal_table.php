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
  Schema::create('jadwal', function (Blueprint $table) {
   $table->string('id_jadwal', 3)->primary();
   $table->string('id_guru', 3);
   $table->string('id_mata_pelajaran', 3);
   $table->string('ruang', 50);
   $table->time('waktu');
   $table->timestamps();

   $table->foreign('id_guru')->references('id_guru')->on('guru')->onDelete('cascade');
   $table->foreign('id_mata_pelajaran')->references('id_mata_pelajaran')->on('mata_pelajaran')->onDelete('cascade');
  });
 }

 /**
  * Reverse the migrations.
  */
 public function down(): void
 {
  Schema::dropIfExists('jadwal');
 }
};
