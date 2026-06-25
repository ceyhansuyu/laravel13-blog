<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tablo Yapısı
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // 2. Varsayılan Kategoriler (Senin hazırladığın şık emojili içerikler)
        DB::table('categories')->insert([
            [
                'id' => 1, 
                'name' => '🤖 Test Category', 
                'slug' => 'test-category', 
                'created_at' => '2026-05-31 13:35:02', 
                'updated_at' => '2026-06-12 06:44:19'
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};