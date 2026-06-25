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
        // 1. Tablo İskeleti
        Schema::create('ad_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('header_active')->default(false);
            $table->longText('header_code')->nullable();
            $table->boolean('sidebar_active')->default(false);
            $table->longText('sidebar_code')->nullable();
            $table->boolean('content_active')->default(false);
            $table->longText('content_code')->nullable();
            $table->boolean('footer_active')->default(false);
            $table->longText('footer_code')->nullable();
            $table->integer('ad_frequency')->default(5);
            $table->integer('max_ads')->default(2);
            $table->longText('ads_txt_content')->nullable();
            $table->timestamps();
        });

        // 2. Varsayılan Veriler (Senin hazırladığın Tailwind placeholder'ları)
        DB::table('ad_settings')->insert([
            'id' => 1,
            'header_active' => 1,
            'header_code' => '<div class="py-[0.5px]"></div><div class="content-ad w-full max-w-4xl mx-auto mt-8 mb-8 p-8 bg-gray-50 dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg flex items-center justify-center transition-colors duration-200"><div class="flex items-center space-x-2 text-gray-400 dark:text-gray-600 font-medium tracking-wide text-xs uppercase select-none"><svg class="w-4 h-4 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg><span>Header Banner Ad</span></div></div>',
            'sidebar_active' => 0,
            'sidebar_code' => null,
            'content_active' => 1,
            'content_code' => '<div class="w-full max-w-3xl mx-auto my-8 p-8 bg-gray-100 dark:bg-gray-900 border-2 border-dashed border-gray-300 dark:border-gray-800 rounded-lg flex items-center justify-center transition-colors duration-200"><div class="flex items-center space-x-2 text-gray-400 dark:text-gray-600 font-medium tracking-wide text-xs uppercase select-none"><svg class="w-4 h-4 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg><span>Content Ad</span></div></div>',
            'footer_active' => 1,
            'footer_code' => '<div class="content-ad  w-full max-w-4xl mx-auto my-8 p-8 bg-gray-50 dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg flex items-center justify-center transition-colors duration-200"><div class="flex items-center space-x-2 text-gray-400 dark:text-gray-600 font-medium tracking-wide text-xs uppercase select-none"><svg class="w-4 h-4 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg><span>Footer Banner Ad</span></div></div>',
            'ad_frequency' => 2,
            'max_ads' => 3,
            'ads_txt_content' => 'Ads.txt Content',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_settings');
    }
};