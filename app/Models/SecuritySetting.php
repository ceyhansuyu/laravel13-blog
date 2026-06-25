<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SecuritySetting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Model olaylarını dinle ve veri güncellendiğinde cache'i temizle.
     */
    protected static function booted()
    {
        // Bir ayar güncellendiğinde veya oluşturulduğunda cache'i temizle
        static::saved(function ($setting) {
            Cache::forget('setting_' . $setting->key);
        });

        // Bir ayar silinirse cache'i temizle
        static::deleted(function ($setting) {
            Cache::forget('setting_' . $setting->key);
        });
    }

    /**
     * Ayarı cache'ten getir, yoksa veritabanından al.
     */
    public static function getSetting(string $key, $default = null)
    {
        return Cache::remember('setting_' . $key, 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }
}