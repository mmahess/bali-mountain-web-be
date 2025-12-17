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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            
            // Kolom Standar
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('thumbnail');
            $table->boolean('is_important')->default(false);
            
            // --- KOLOM BARU YANG KITA BUTUHKAN ---
            // 1. Kategori (Default: Tips)
            $table->string('category')->default('Tips');
            
            // 2. Penulis (User ID)
            // nullable() agar aman jika user dihapus
            // constrained() otomatis relasi ke tabel users
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};