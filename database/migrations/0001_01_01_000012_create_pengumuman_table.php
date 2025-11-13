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
  Schema::create('pengumuman', function (Blueprint $table) {
   $table->string('id_pengumuman', 3)->primary();
   $table->string('judul', 255);
   $table->text('isi');
   $table->date('tanggal');
   $table->string('id_admin', 3);
   $table->timestamps();

   $table->foreign('id_admin')->references('id_admin')->on('admin')->onDelete('cascade');
  });
 }

 /**
  * Reverse the migrations.
  */
 public function down(): void
 {
  Schema::dropIfExists('pengumuman');
 }
};
