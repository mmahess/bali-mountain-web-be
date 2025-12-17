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
    Schema::table('hiking_trails', function (Blueprint $table) {
        $table->string('estimation_time')->default('6-8 Jam')->after('distance');
    });
    }   

    /**
     * Reverse the migrations.
     */
    public function down()
    {
    Schema::table('hiking_trails', function (Blueprint $table) {
        $table->dropColumn(['estimation_time']);
    });
    }
};
