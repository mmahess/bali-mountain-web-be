<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('galleries', function (Blueprint $table) {
            // Menambahkan kolom user_id setelah kolom id
            // nullable() dipasang agar jika ada data lama, tidak error
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            
            // Menjadikan foreign key (opsional tapi bagus)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};