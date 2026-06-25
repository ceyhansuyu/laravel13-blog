<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;

class SettingsController extends Controller
{
    public function edit()
    {
        Gate::authorize('is-admin');
        // Mevcut tüm ayarları veritabanından çekiyoruz
        $settings = [
            'pagination_limit'    => Setting::getVal('pagination_limit', 9),
            'search_fields'       => Setting::getVal('search_fields', ['title', 'slug', 'content']),
            'enable_search'       => Setting::getVal('enable_search', true),
            'maintenance_mode'    => Setting::getVal('maintenance_mode', false),
            'maintenance_message' => Setting::getVal('maintenance_message', __('Our site is currently being updated.')),
            'enable_registration' => Setting::getVal('enable_registration', true),
            'webp_quality'        => Setting::getVal('webp_quality', 80),
            'site_name'           => Setting::getVal('site_name', 'Mini Blog'),
            'site_description'    => Setting::getVal('site_description', ''),
            'google_analytics_id' => Setting::getVal('google_analytics_id', ''),
            'hcaptcha_site_key'  => Setting::getVal('hcaptcha_site_key', ''),
            'hcaptcha_secret_key'=> Setting::getVal('hcaptcha_secret_key', ''),
            'enable_social_share' => Setting::getVal('enable_social_share', true),
            'enable_author_card' => Setting::getVal('enable_author_card', true),
            'allow_submit_comments' => Setting::getVal('allow_submit_comments', true),
            'allow_show_comments' => Setting::getVal('allow_show_comments', true),
            'show_post_date' => Setting::getVal('show_post_date', true),
            'show_updated_date' => Setting::getVal('show_updated_date', true),
            'comment_moderation' => Setting::getVal('comment_moderation', ['pending', 'approved']),

            ];

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        Gate::authorize('is-admin');
        $request->validate([
            'pagination_limit' => 'required|integer|min:1|max:100',
            'search_fields' => 'required|array|min:1',
            'search_fields.*' => 'in:title,slug,content',
            'maintenance_message' => 'nullable|string|max:255',
            'webp_quality' => 'required|integer|min:10|max:100',
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'google_analytics_id' => 'nullable|string|max:50',
            'hcaptcha_site_key' => 'nullable|string|max:100',
            'hcaptcha_secret_key' => 'nullable|string|max:100',
            'toast_duration' => 'required|integer|min:500|max:20000',
        ]);

        // Tüm girdileri bir dizide toplayalım
        $allSettings = $request->except(['_token', '_method']);

        // Checkbox'lar için manuel düzeltme (gelmeyenler false olsun)
        $allSettings['enable_search'] = $request->has('enable_search');
        $allSettings['maintenance_mode'] = $request->has('maintenance_mode');
        $allSettings['enable_registration'] = $request->has('enable_registration');
        $allSettings['enable_social_share'] = $request->has('enable_social_share');
        $allSettings['enable_author_card'] = $request->has('enable_author_card');
        $allSettings['allow_submit_comments'] = $request->has('allow_submit_comments');
        $allSettings['allow_show_comments'] = $request->has('allow_show_comments');
        $allSettings['show_post_date'] = $request->has('show_post_date');
        $allSettings['show_updated_date'] = $request->has('show_updated_date');
        
        // DİKKAT: Checkbox işaretliyse array formatında string olarak kaydet, değilse null yap.
        $allSettings['comment_moderation'] = $request->has('comment_moderation') ? '["pending", "approved"]' : null;

        // Döngü ile tek seferde kaydet
        foreach ($allSettings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('admin.settings.edit')
            ->with('success', __('Settings updated successfully.'))
            ->with('toast', __('Settings updated successfully.'));
    }

    public function clearCache()
    {
        Gate::authorize('is-admin');
        // Uygulamanın tüm önbelleklerini roket hızında temizle
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        
        return redirect()->route('admin.settings.edit')
            ->with('success', __('System cache cleared successfully.'))
            ->with('toast', __('System cache cleared successfully.'));
    }

    // Yeni eklenen Optimize Et metodu
    public function optimize()
    {
        Gate::authorize('is-admin');
        // php artisan optimize komutunu HTTP yanıtı gönderildikten ve oturum güvenle kaydedildikten SONRA arkada tetikler
        app()->terminating(function () {
            Artisan::call('optimize');
        });

        return redirect()->route('admin.settings.edit')
            ->with('success', __('System optimized successfully.'))
            ->with('toast', __('System optimized successfully.'));
    }
}