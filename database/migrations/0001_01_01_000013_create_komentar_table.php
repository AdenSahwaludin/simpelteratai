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
  Schema::create('komentar', function (Blueprint $table) {
   $table->string('id_komentar', 4)->primary();
   $table->string('id_orang_tua', 4);
   $table->text('komentar');
   $table->timestamps();

   $table->foreign('id_orang_tua')->references('id_orang_tua')->on('orang_tua')->onDelete('cascade');
  });
 }

 /**
  * Reverse the migrations.
  */
 public function down(): void
 {
  Schema::dropIfExists('komentar');
 }
};
