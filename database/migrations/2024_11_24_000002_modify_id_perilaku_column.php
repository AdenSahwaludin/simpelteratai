<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('perilaku', function (Blueprint $table) {
            $table->string('id_perilaku', 5)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perilaku', function (Blueprint $table) {
            $table->string('id_perilaku', 3)->change();
        });
    }
};
