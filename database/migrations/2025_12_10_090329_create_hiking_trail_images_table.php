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
        Schema::create('hiking_trail_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hiking_trail_id')->constrained()->onDelete('cascade');
            $table->string('image_url'); // Link foto
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hiking_trail_images');
    }
};
