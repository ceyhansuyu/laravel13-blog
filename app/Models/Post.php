<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AdSetting;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // BelongsTo ilişkisi için ekledik

use App\Services\TocGenerator;
use Illuminate\Database\Eloquent\Casts\Attribute;

#[Fillable(['title', 'slug', 'content', 'status', 'is_featured', 'category_id', 'user_id', 'meta_description'])]
class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'content', 'status', 'is_featured', 'category_id', 'user_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Bu fonksiyonu ekleyerek views ilişkisini kuruyoruz:
    public function views(): HasMany
    {
        return $this->hasMany(PostView::class);
    }

    /**
     * Yazının yazarına (Kullanıcıya) olan ilişkisi
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function contentWithToc(): Attribute
    {
        return Attribute::get(function () {
            $result = TocGenerator::generate($this->content);
            return $result['html'];
        });
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->where('status', 'approved')->orderBy('created_at', 'desc');
    }


    /**
     * Blog içeriğine önce TOC (İçindekiler), sonra Reklamları otomatik yerleştiren Accessor
     */
    public function getContentWithAdsAttribute()
    {
        // İŞTE BÜTÜN SIR BURADA DOSTUM:
        // Ham içerik ($this->content) yerine, senin çalışan TOC accessor'ını çağırıyoruz.
        // Böylece $content değişkeni, içindekiler tablosu çoktan eklenmiş haliyle geliyor.
        $content = $this->content_with_toc; 

        // Reklam ayarlarını çekiyoruz
        $settings = \App\Models\AdSetting::first();

        // 1. KONTROL: Ayarlar yoksa, içerik reklamı pasifse veya reklam kodu boşsa 
        // doğrudan İçindekiler (TOC) eklenmiş halini döndür!
        if (!$settings || !$settings->content_active || empty(trim($settings->content_code))) {
            return $content;
        }

        // Veritabanından gelen dinamik değerler
        $adCode   = $settings->content_code;
        $interval = $settings->ad_frequency; // Kaç paragrafta bir çıkacak?
        $limit    = $settings->max_ads;      // Toplam kaç tane çıkacak?

        // Parçalama mantığın (TOC genelde <ul> veya <div> olduğu için </p> sayımını bozmaz, kusursuz çalışır)
        $paragraphs = explode('</p>', $content);
        $totalParagraphs = count(array_filter($paragraphs, 'trim'));
        
        $newContent = '';
        $insertedAdsCount = 0;

        foreach ($paragraphs as $index => $paragraph) {
            if (trim($paragraph)) {
                $newContent .= $paragraph . '</p>';
            }

            $currentParaNumber = $index + 1;

            // ŞARTLAR:
            // 1. Aralık tutuyor mu?
            // 2. Footer'a yapışmasın (< total)
            // 3. Limit doldu mu?
            if ($currentParaNumber % $interval == 0 && 
                $currentParaNumber < $totalParagraphs && 
                $insertedAdsCount < $limit) {
                
                // Tailwind ile ufak bir boşluk vererek reklam alanını basıyoruz
                $newContent .= '<div class="content-ad my-8 flex justify-center">';
                $newContent .=      $adCode;
                $newContent .= '</div>';
                
                $insertedAdsCount++;
            }
        }

        return $newContent;
    }








}