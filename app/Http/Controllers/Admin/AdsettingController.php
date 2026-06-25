<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdSetting; // Yeni modelimizi dahil ettik
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class AdsettingController extends Controller
{
    // 1. Reklam Ayarları Sayfasını Gösterme
    public function index()
    {
        // Tablodaki ilk kaydı çekiyoruz, eğer veritabanı boşsa boş bir instance oluşturuyoruz
        $settings = AdSetting::first() ?? new AdSetting();

        return view('admin.ads.index', compact('settings'));
    }

    // 2. Reklam Ayarlarını Güncelleme
    public function update(Request $request)
    {
        Gate::authorize('is-admin');

        $request->validate([
            'header_code'     => 'nullable|string',
            'sidebar_code'    => 'nullable|string',
            'content_code'    => 'nullable|string',
            'footer_code'     => 'nullable|string',
            'ad_frequency'    => 'required|integer|min:1',
            'max_ads'         => 'required|integer|min:1',
            'ads_txt_content' => 'nullable|string',
        ]);

        // Verileri tek bir dizi içinde topluyoruz (Döngü yok, tek sorgu!)
        $dataToSave = [
            'header_active'   => $request->has('header_active'),
            'header_code'     => $request->input('header_code'),
            'sidebar_active'  => $request->has('sidebar_active'),
            'sidebar_code'    => $request->input('sidebar_code'),
            'content_active'  => $request->has('content_active'),
            'content_code'    => $request->input('content_code'),
            'footer_active'   => $request->has('footer_active'),
            'footer_code'     => $request->input('footer_code'),
            'ad_frequency'    => $request->input('ad_frequency', 5),
            'max_ads'         => $request->input('max_ads', 2),
            'ads_txt_content' => $request->input('ads_txt_content'),
        ];

        // 1 numaralı id'ye sahip satırı günceller veya yoksa oluşturur.
        AdSetting::updateOrCreate(['id' => 1], $dataToSave);

        // ads.txt optimizasyonu aynen devam
        $this->generateAdsTxtPhysicalFile($dataToSave['ads_txt_content']);

        // Cache'i sıfırlıyoruz ki güncel ayarlar anında siteye yansısın
        Cache::forget('site_ad_settings');

        return redirect()->back()->with('toast', __('Ad settings updated successfully.'));
    }

    // 3. Fiziksel ads.txt Dosyası Oluşturucu
    protected function generateAdsTxtPhysicalFile($content)
    {
        $filePath = public_path('ads.txt');
        
        if (!empty(trim($content))) {
            File::put($filePath, trim($content));
        } else {
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }
    }
}