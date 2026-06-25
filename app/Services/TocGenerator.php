<?php

namespace App\Services;

use DOMDocument;
use Illuminate\Support\Str;

class TocGenerator
{
    public static function generate(string $content): array
    {
        if (empty($content)) {
            return ['html' => $content, 'toc' => ''];
        }

        libxml_use_internal_errors(true);
        $dom = new DOMDocument();

        // 1. ADIM: Türkçe karakterleri koru
        $contentWithEntities = mb_encode_numericentity(
            $content, 
            [0x80, 0x10FFFF, 0, ~0], 
            'UTF-8'
        );
        
        // 2. ADIM (KRİTİK DÜZELTME): 
        // İçeriği geçici bir <div> içine alıyoruz. 
        $wrappedContent = '<div id="temp-wrapper">' . $contentWithEntities . '</div>';

        // Parametreler aynı kalıyor
        $dom->loadHTML($wrappedContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $headings = $dom->getElementsByTagName('*');
        $tocList = [];

        foreach ($headings as $element) {
            // Güvenlik dokunuşu: Etiket adını küçük harfe çevirerek kontrol et
            if (in_array(strtolower($element->tagName), ['h2', 'h3'])) {
                
                $text = $element->textContent;
                
                // Ekstra güvenlik: Başlık içindeki boşlukları temizle
                $text = trim(preg_replace('/\s+/', ' ', $text));
                
                $slug = Str::slug($text);
                $element->setAttribute('id', $slug);

                $tocList[] = [
                    'level' => (int) substr($element->tagName, 1),
                    'text'  => $text,
                    'slug'  => $slug
                ];
            }
        }

        // --- ÇIKIŞ İŞLEMİ ---
        
        if (empty($tocList)) {
            libxml_clear_errors();
            return ['html' => $content, 'toc' => ''];
        }

        // Tamamen Tailwind sınıflarıyla donatılmış yeni TOC Kutusu
        $tocHtml = '<div class="toc-wrapper not-prose my-8 p-5 w-full max-w-2xl rounded-lg border border-gray-200 bg-gray-50/70 dark:border-zinc-700 dark:bg-zinc-800/40">';
        $tocHtml .= '<p class="m-0 mb-3 text-base font-bold text-gray-900 dark:text-zinc-100">' . __('Table of Contents') . '</p>';
        $tocHtml .= '<ul class="m-0 list-none p-0 flex flex-col gap-2">';

        foreach ($tocList as $item) {
            // H3 ise sağa doğru padding (pl-5) veriyoruz
            $liClass = $item['level'] === 3 ? 'pl-5' : 'pl-0';
            
            $safeText = htmlspecialchars($item['text'], ENT_QUOTES, 'UTF-8');
            $safeSlug = htmlspecialchars($item['slug'], ENT_QUOTES, 'UTF-8');
            
            $tocHtml .= "<li class='{$liClass} list-none m-0 p-0'>";
            
            // Linklerin renkleri, hover efektleri ve Alpine.js yumuşak kayma kodu
            $tocHtml .= "<a href='#{$safeSlug}' 
                            @click.prevent=\"document.getElementById('{$safeSlug}')?.scrollIntoView({ behavior: 'smooth', block: 'start' })\"
                            class='text-[0.95rem] font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 no-underline hover:underline transition-colors duration-200'>
                            {$safeText}
                         </a>";
            $tocHtml .= "</li>";
        }
        $tocHtml .= '</ul></div>';
        
        // 3. ADIM (KRİTİK DÜZELTME):
        // Eklediğimiz geçici <div>'i (wrapper) kaldırıp içindeki HTML'i alıyoruz.
        $container = $dom->getElementsByTagName('div')->item(0);
        $processedHtml = '';
        
        if ($container) {
            foreach ($container->childNodes as $child) {
                $processedHtml .= $dom->saveHTML($child);
            }
        } else {
            $processedHtml = $dom->saveHTML();
        }
        
        libxml_clear_errors();

        // 4. ADIM: TOC'u İlk Resimden Sonra Yerleştirme Mantığı
        $finalHtml = '';
        
        // İlk <img> etiketini (ister self-closing /> olsun, ister > olsun) yakalıyoruz
        if (preg_match('/<img[^>]*>/i', $processedHtml, $matches, PREG_OFFSET_CAPTURE)) {
            $imgTagString = $matches[0][0];
            $imgTagOffset = $matches[0][1];
            
            // Resmin bittiği tam pozisyon
            $cutPosition = $imgTagOffset + strlen($imgTagString);
            
            // HTML'i resmin olduğu yerden ikiye bölüp araya TOC wrapper'ını enjekte ediyoruz
            $finalHtml = substr($processedHtml, 0, $cutPosition) . 
                         $tocHtml . 
                         substr($processedHtml, $cutPosition);
        } else {
            // Eğer yazıda hiç resim yoksa, TOC listen en başa güvenli bir şekilde eklenir
            $finalHtml = $tocHtml . $processedHtml;
        }

        return [
            'html' => $finalHtml, 
            'toc'  => $tocHtml // İstersen blade tarafında bağımsız kullanabilmen için yine de ayrı dönüyoruz
        ];
    }
}