<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
    Schema::create('galleries', function (Blueprint $table) {
        $table->id();
        $table->string('caption'); // Untuk menyimpan caption
        $table->string('image');   // INI KUNCINYA (Harus 'image' agar cocok dengan controller)
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
