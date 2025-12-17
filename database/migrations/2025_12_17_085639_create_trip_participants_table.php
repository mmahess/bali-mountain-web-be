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
    Schema::create('trip_participants', function (Blueprint $table) {
        $table->id();
        $table->foreignId('open_trip_id')->constrained('open_trips')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->timestamps(); // Mencatat kapan dia gabung
        
        // Mencegah user gabung 2 kali di trip yang sama
        $table->unique(['open_trip_id', 'user_id']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_participants');
    }
};
