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
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            
            $table->enum('status', ['draft', 'publish'])->default('draft');
            $table->enum('is_featured', ['normal', 'featured'])->default('normal');
            
            $table->text('meta_description')->nullable();
            $table->unsignedInteger('view_count')->default(0);
            $table->timestamps();

            // İlişkiler (Foreign Keys)
            // Not: users ve categories tablolarının migration'ları bu dosyadan önce çalışmalıdır.
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};