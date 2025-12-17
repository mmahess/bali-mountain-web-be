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
    Schema::create('open_trips', function (Blueprint $table) {
        $table->id();
        
        // Relasi
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('hiking_trail_id')->constrained('hiking_trails')->onDelete('cascade');
        
        // Data sesuai Gambar
        $table->string('title');              // Judul Trip
        $table->string('meeting_point');      // Mepo
        $table->date('trip_date');            // Tanggal Mulai
        $table->integer('max_participants');  // Slot Peserta
        $table->string('group_chat_link');    // Link Grup Chat (Whatsapp)
        $table->text('description');          // Detail Rencana
        
        // Status default
        $table->enum('status', ['open', 'full', 'finished'])->default('open');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('open_trips');
    }
};
