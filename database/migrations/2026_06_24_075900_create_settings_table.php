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
        // Tablo yapısını oluşturuyoruz
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('value')->nullable();
            $table->timestamps();
        });

        // Tablo verilerini eksiksiz bir şekilde içeri aktarıyoruz
        DB::table('settings')->insert([
            ['id' => 1, 'key' => 'pagination_limit', 'value' => '"9"', 'created_at' => '2026-05-31 13:14:32', 'updated_at' => '2026-06-21 15:33:06'],
            ['id' => 2, 'key' => 'search_fields', 'value' => '["title", "slug", "content"]', 'created_at' => '2026-05-31 13:14:32', 'updated_at' => '2026-05-31 13:24:53'],
            ['id' => 3, 'key' => 'enable_search', 'value' => 'true', 'created_at' => '2026-05-31 13:18:34', 'updated_at' => '2026-06-24 03:15:28'],
            ['id' => 4, 'key' => 'maintenance_mode', 'value' => 'false', 'created_at' => '2026-05-31 13:18:34', 'updated_at' => '2026-06-21 16:16:26'],
            ['id' => 5, 'key' => 'maintenance_message', 'value' => '"Our site is currently being updated. Please visit again later."', 'created_at' => '2026-05-31 13:22:29', 'updated_at' => '2026-06-19 16:36:12'],
            ['id' => 6, 'key' => 'enable_registration', 'value' => 'true', 'created_at' => '2026-05-31 13:43:24', 'updated_at' => '2026-06-23 19:51:26'],
            ['id' => 7, 'key' => 'webp_quality', 'value' => '"80"', 'created_at' => '2026-06-01 23:57:30', 'updated_at' => '2026-06-20 03:59:08'],
            ['id' => 8, 'key' => 'site_name', 'value' => '"Mini Blog"', 'created_at' => '2026-06-09 21:46:23', 'updated_at' => '2026-06-21 13:21:35'],
            ['id' => 9, 'key' => 'site_description', 'value' => '"Demo Site Description"', 'created_at' => '2026-06-09 21:46:23', 'updated_at' => '2026-06-10 22:42:51'],
            ['id' => 10, 'key' => 'google_analytics_id', 'value' => '"G-1A2B3C4D5E"', 'created_at' => '2026-06-09 21:46:23', 'updated_at' => '2026-06-10 19:26:56'],
            ['id' => 11, 'key' => 'hcaptcha_site_key', 'value' => '""', 'created_at' => '2026-06-09 21:46:23', 'updated_at' => '2026-06-11 04:09:10'],
            ['id' => 12, 'key' => 'hcaptcha_secret_key', 'value' => '""', 'created_at' => '2026-06-09 21:46:23', 'updated_at' => '2026-06-11 04:09:10'],
            ['id' => 13, 'key' => 'enable_social_share', 'value' => 'true', 'created_at' => '2026-06-11 04:28:10', 'updated_at' => '2026-06-11 18:19:54'],
            ['id' => 14, 'key' => 'enable_author_card', 'value' => 'true', 'created_at' => '2026-06-11 04:28:10', 'updated_at' => '2026-06-21 13:48:55'],
            ['id' => 15, 'key' => 'allow_submit_comments', 'value' => 'true', 'created_at' => '2026-06-21 12:44:51', 'updated_at' => '2026-06-24 03:13:01'],
            ['id' => 16, 'key' => 'allow_show_comments', 'value' => 'true', 'created_at' => '2026-06-21 12:44:51', 'updated_at' => '2026-06-21 14:32:28'],
            ['id' => 17, 'key' => 'show_post_date', 'value' => 'true', 'created_at' => '2026-06-21 12:44:51', 'updated_at' => '2026-06-23 23:19:05'],
            ['id' => 18, 'key' => 'show_updated_date', 'value' => 'true', 'created_at' => '2026-06-21 12:44:51', 'updated_at' => '2026-06-24 02:45:57'],
            ['id' => 19, 'key' => 'toast_duration', 'value' => '"3000"', 'created_at' => '2026-06-21 12:44:51', 'updated_at' => '2026-06-24 03:17:20'],
            ['id' => 20, 'key' => 'comment_moderation', 'value' => '["pending", "approved"]', 'created_at' => '2026-06-21 12:44:51', 'updated_at' => '2026-06-24 03:17:20'],

        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};