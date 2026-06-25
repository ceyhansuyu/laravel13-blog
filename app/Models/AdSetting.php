<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdSetting extends Model
{
    protected $table = 'ad_settings';

    protected $fillable = [
        'header_active',
        'header_code',
        'sidebar_active',
        'sidebar_code',
        'content_active',
        'content_code',
        'footer_active',
        'footer_code',
        'ad_frequency',
        'max_ads',
        'ads_txt_content',
    ];

    // Veritabanından çekerken otomatik veri tipine dönüştürme (Casting)
    protected $casts = [
        'header_active'  => 'boolean',
        'sidebar_active' => 'boolean',
        'content_active' => 'boolean',
        'footer_active'  => 'boolean',
        'ad_frequency'   => 'integer',
        'max_ads'        => 'integer',
    ];
}