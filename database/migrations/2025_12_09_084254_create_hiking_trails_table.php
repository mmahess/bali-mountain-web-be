<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hiking_trails', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('slug')->unique(); 
            $table->longText('description');
            $table->string('location');
            $table->string('starting_point');
            $table->enum('difficulty_level', ['easy', 'medium', 'hard']);
            $table->integer('elevation'); 
            $table->integer('elevation_gain');
            $table->double('distance');
            $table->decimal('ticket_price', 10, 2); 
            $table->boolean('is_guide_required')->default(false); 
            $table->text('map_iframe_url')->nullable(); 
            $table->string('cover_image')->nullable();
            $table->timestamps();
        });
    }
};
