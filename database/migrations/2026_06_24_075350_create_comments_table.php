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
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('post_id');
            $table->string('name');
            $table->string('email');
            $table->text('content');
            $table->string('status')->default('pending'); // Enun yapısı için 'draft', 'publish' mantığına uygun İngilizce string
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->boolean('is_approved')->default(true);
            $table->unsignedBigInteger('user_id')->nullable(); // Giriş yapmış yazarlar/adminler için
            $table->timestamps();

            // İndeksler ve İlişkiler (Foreign Keys)
            // Not: Projende posts ve users tablolarının migration'ları bu dosyadan önce çalışmalıdır.
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};