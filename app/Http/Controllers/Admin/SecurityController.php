<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SecuritySetting;
use App\Models\FailedLoginAttempt; // Yeni veritabanı modelimizi dahil ettik
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class SecurityController extends Controller
{
    /**
     * Güvenlik Paneli ve Ayarlar
     */
    public function edit(Request $request): View
    {
        Gate::authorize('is-admin');
        // Klasik (bool) dönüşümü yerine doğrudan '1' e eşit mi diye bakıyoruz.
        // Böylece veritabanında '0' yazıyorsa burası kesin olarak false dönecek ve switch doğru çalışacak.
        $settings = [
            'brute_force_enabled' => SecuritySetting::getSetting('brute_force_enabled', '1') === '1',
            'max_attempts'        => (int) SecuritySetting::getSetting('login_limit', 5),
            'lockout_time'        => (int) SecuritySetting::getSetting('login_decay', 1),
        ];

        // Statik diziyi kaldırıp gerçek veritabanı loglarını senin Blade formatına göre map ederek çektik dostum
        $failedAttempts = FailedLoginAttempt::latest()
            ->get()
            ->map(function ($attempt) {
                return [
                    'time'     => $attempt->created_at->format('d.m.Y H:i'),
                    'ip'       => $attempt->ip_address,
                    'username' => $attempt->username,
                    'browser'  => $attempt->user_agent,
                ];
            })
            ->toArray(); // Array yapısıyla döndürdüğümüz için Blade döngün aynen çalışmaya devam edecek

        return view('admin.security.edit', [
            'user'           => $request->user(),
            'settings'       => $settings,
            'failedAttempts' => $failedAttempts
        ]);
    }

    /**
     * Brute Force Ayarlarını Güncelleme
     */
    public function updateSettings(Request $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize('is-admin');
        $validated = $request->validate([
            'brute_force_enabled' => 'required|boolean',
            'max_attempts'        => 'required|integer|min:1|max:20',
            'lockout_time'        => 'required|integer|min:1|max:1440',
        ]);

        // Tuzağı bozduğumuz yer burası dostum: 
        // Gelen boolean değeri doğrudan değil, '? '1' : '0'' mantığıyla net bir string olarak kaydediyoruz.
        
        // 1. Güvenlik Duvarı Durumu
        SecuritySetting::updateOrCreate(
            ['key' => 'brute_force_enabled'],
            ['value' => $validated['brute_force_enabled'] ? '1' : '0']
        );

        // 2. Maksimum Deneme Sınırı (login_limit)
        SecuritySetting::updateOrCreate(
            ['key' => 'login_limit'],
            ['value' => (string) $validated['max_attempts']]
        );

        // 3. Engellenme Süresi (login_decay)
        SecuritySetting::updateOrCreate(
            ['key' => 'login_decay'],
            ['value' => (string) $validated['lockout_time']]
        );

        // 2. İŞTE TAM BURADA CACHE'İ SİLİYORUZ!
        // Ayarlar güncellendiği an eski cache'i uçuruyoruz ki AppServiceProvider anında yeni ayarları görebilsin.
        Cache::forget('admin_security_settings');

        return Redirect::route('admin.security.edit')
            ->with('toast', __('Brute force protection settings updated successfully.'));         
    }

    /**
     * Hatalı Giriş Loglarını Temizleme
     */
    public function clearLogs(): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize('is-admin');
        // Veritabanındaki tüm hatalı giriş log tablosunu sıfırlıyoruz
        FailedLoginAttempt::truncate();

        return Redirect::route('admin.security.edit')
            ->with('toast', __('Security logs cleared successfully.')); 
    }
}