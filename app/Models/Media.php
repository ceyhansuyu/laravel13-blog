<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    // Mevcut fillable dizini koruyoruz
    protected $fillable = [
        'name',
        'file_path',
        'webp_path',
        'original_path',
        'mime_type',
        'size',
        'width',
        'height',
        'format'
    ];

    // Frontend'e ekstra olarak gidecek özel alanlar
    protected $appends = ['url', 'size_formatted', 'dimensions'];

    /**
     * url çağrıldığında çalışır (appends içinde ekli)
     */
    public function getUrlAttribute()
    {
        // Aşağıdaki accessors (erişimciler) sayesinde webp_path ve file_path 
        // zaten "https://..." şeklinde tam URL olarak dönüyor. 
        // Bu yüzden tekrar Storage::url() sarmalına almıyoruz.
        return $this->webp_path ?? $this->file_path;
    }

    /**
     * size_formatted çağrıldığında çalışır
     */
    public function getSizeFormattedAttribute()
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * dimensions çağrıldığında çalışır
     */
    public function getDimensionsAttribute()
    {
        if ($this->width && $this->height) {
            return $this->width . ' × ' . $this->height . ' px';
        }
        return __('Unknown');
    }

    /**
     * file_path çağrıldığında otomatik olarak tam URL döner.
     */
    protected function filePath(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Storage::disk('public')->url($value) : null,
        );
    }

    /**
     * webp_path çağrıldığında otomatik olarak tam URL döner.
     */
    protected function webpPath(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Storage::disk('public')->url($value) : null,
        );
    }

    /**
     * original_path çağrıldığında otomatik olarak tam URL döner.
     */
    protected function originalPath(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Storage::disk('public')->url($value) : null,
        );
    }
}