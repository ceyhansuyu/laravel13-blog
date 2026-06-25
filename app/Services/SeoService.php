<?php

namespace App\Services;

class SeoService
{
    public function generateMetaDescription(string $htmlContent, int $limit = 25): string
    {
        // 1. HTML etiketlerini temizle
        $plainText = strip_tags($htmlContent);
        
        // 2. Kelimelere ayır
        $words = explode(' ', preg_replace('/\s+/', ' ', trim($plainText)));
        
        // 3. Yazı zaten kısaysa (limit altındaysa), tamamını al
        if (count($words) <= $limit) {
            return trim($plainText);
        }
        
        // 4. Uzunsa, belirtilen kelime sayısı kadar al ve üç nokta ekle
        return implode(' ', array_slice($words, 0, $limit)) . '...';
    }
}