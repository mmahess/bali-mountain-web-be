<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('open_trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('hiking_trail_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->dateTime('trip_date');
            $table->string('meeting_point');
            $table->integer('max_participants');
            $table->text('description')->nullable();
            $table->string('contact_person');
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamps();
        });
    }
};
