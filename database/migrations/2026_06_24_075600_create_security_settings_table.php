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
        Schema::create('security_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('key')->unique(); // Key değerlerini unique yaptık ki çakışma olmasın
            $table->string('value');
            $table->timestamps();
        });

        // 2. Varsayılan Güvenlik Ayarları (Senin belirlediğin değerler)
        $now = now();
        DB::table('security_settings')->insert([
            [
                'id' => 1, 
                'key' => 'brute_force_enabled', 
                'value' => '1', 
                'created_at' => $now, 
                'updated_at' => $now
            ],
            [
                'id' => 2, 
                'key' => 'login_limit', 
                'value' => '5', 
                'created_at' => $now, 
                'updated_at' => $now
            ],
            [
                'id' => 3, 
                'key' => 'login_decay', 
                'value' => '2', 
                'created_at' => $now, 
                'updated_at' => $now
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_settings');
    }
};