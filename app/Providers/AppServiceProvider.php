<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\SecuritySetting;
use App\Models\FailedLoginAttempt;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Failed;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;

use App\Models\Setting;
use App\Observers\SettingObserver;

use Illuminate\Support\Facades\Request as FacadeRequest; 
use Illuminate\Http\Request;
use App\Models\Post;


use Illuminate\Support\Facades\Gate;
use App\Models\User;





class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // --- 1. Dinamik Admin Giriş Sınırlandırıcısı ---
        RateLimiter::for('admin-login', function (Request $request) {
            $securitySettings = Cache::remember('admin_security_settings', 600, function () {
                return [
                    'enabled' => (bool) SecuritySetting::getSetting('brute_force_enabled', true),
                    'limit'   => (int) SecuritySetting::getSetting('login_limit', 5),
                    'decay'   => (int) SecuritySetting::getSetting('login_decay', 1),
                ];
            });
            
            if (!$securitySettings['enabled']) {
                return Limit::none();
            }

            return Limit::perMinutes($securitySettings['decay'], $securitySettings['limit'])
                        ->by($request->ip());
        });

        // --- 2. Hatalı Giriş Denemelerini Kaydetme ---
        Event::listen(Failed::class, function (Failed $event) {
            FailedLoginAttempt::create([
                'ip_address' => FacadeRequest::ip(),
                'username'   => $event->credentials['email'] ?? $event->credentials['username'] ?? 'Bilinmiyor',
                'user_agent' => FacadeRequest::userAgent(),
            ]);
        });

        // Konsol komutlarında (composer, artisan vb.) veritabanı sorgusu atmasını engelliyoruz
        if (! app()->runningInConsole()) {
            
            // --- 3. Global Ayarlar (Cache ile Performanslı) ---
            if (Schema::hasTable('settings')) {
                $siteSettings = Cache::remember('site_global_settings', 3600, function () {
                    $settings = Setting::pluck('value', 'key')->all();

                    // Doğru (Sadece ihtiyacın olanı seç):
                $siteSettings = Setting::whereIn('key', ['site_name', 'site_description', 'enable_author_card','enable_registration' ,
                                                             'enable_social_share', 'google_analytics_id', 'allow_submit_comments', 
                                                             'allow_show_comments', 'hcaptcha_site_key', 'show_post_date', 'show_updated_date',
                                                             'toast_duration'
                                                            ])
                      ->pluck('value', 'key')
                      ->toArray();
                    
                    // Burayı object yerine array yapıyoruz
                    return [
                        'site_name'         => $settings['site_name'] ?? 'Blogum',
                        'site_description'  => $settings['site_description'] ?? '',
                        'google_analytics_id' => $settings['google_analytics_id'] ?? null,
                        'hcaptcha_site_key'  => $settings['hcaptcha_site_key'] ?? null,
                        'enable_registration'  => (bool) ($settings['enable_registration'] ?? false),
                        'enable_author_card'  => (bool) ($settings['enable_author_card'] ?? false),
                        'enable_social_share' => (bool) ($settings['enable_social_share'] ?? false),
                        'allow_submit_comments' => (bool) ($settings['allow_submit_comments'] ?? false),
                        'allow_show_comments' => (bool) ($settings['allow_show_comments'] ?? false),
                        'show_post_date' => (bool) ($settings['show_post_date'] ?? false),
                        'show_updated_date' => (bool) ($settings['show_updated_date'] ?? false),
                        'toast_duration'  => $settings['toast_duration'] ?? 500,

                    ];
                });

                View::share('siteSettings', $siteSettings);
            }


            // --- 4. Reklam Ayarları (Cache ile Performanslı) ---
            if (Schema::hasTable('ad_settings')) {
                $ad_settings = Cache::remember('site_ad_settings', 3600, function () {
                    // ad_settings tablosundaki ilk kaydı alıyoruz.
                    // Model adının AdSetting olduğunu varsayıyorum (App\Models\AdSetting). 
                    $Adsettings = \App\Models\AdSetting::first();

                    // Eğer tabloda henüz kayıt yoksa hata vermemesi için resimdeki varsayılan (default) değerleri döndürüyoruz.
                    return [
                        'header_active'   => (bool) ($Adsettings->header_active ?? false),
                        'header_code'     => $Adsettings->header_code ?? null,
                        
                        'sidebar_active'  => (bool) ($Adsettings->sidebar_active ?? false),
                        'sidebar_code'    => $Adsettings->sidebar_code ?? null,
                        
                        'content_active'  => (bool) ($Adsettings->content_active ?? false),
                        'content_code'    => $Adsettings->content_code ?? null,
                        
                        'footer_active'   => (bool) ($Adsettings->footer_active ?? false),
                        'footer_code'     => $Adsettings->footer_code ?? null,
                        
                        'ad_frequency'    => (int) ($Adsettings->ad_frequency ?? 5),
                        'max_ads'         => (int) ($Adsettings->max_ads ?? 2),
                        
                        'ads_txt_content' => $Adsettings->ads_txt_content ?? null,
                    ];
                });

                View::share('ad_settings', $ad_settings);
            }
            
        } // if (! app()->runningInConsole()) bloğunun sonu


        // --- 5. Layout ve Meta Yönetimi ---
        View::composer('components.layouts.posts', function ($view) {
            // Artık global 'siteSettings' elimizin altında olduğu için tekrar veritabanına gitmiyoruz
            $isPostShowPage = request()->routeIs('post.show', 'posts.show');
            $view->with('isPostShowPage', $isPostShowPage);

            $metaDescription = null;
            $pageTitle = null;

            if ($isPostShowPage) {
                $postParam = request()->route('post') ?? request()->route('blog') ?? request()->route('id');
                
                if ($postParam) {
                    $post = $postParam instanceof Post ? $postParam : Post::find($postParam);
                    
                    if ($post) {
                        $metaDescription = $post->meta_description ?? null;
                        $pageTitle = $post->title;
                    }
                }
            }

            $view->with('metaDescription', $metaDescription);
            $view->with('pageTitle', $pageTitle);
        });

        Setting::observe(SettingObserver::class);


        // 1. GLOBAL KAPI: Admin paneline/dashboard'a kimler ayak basabilir?
        // Admin, Yazar ve Standart Üye giriş yapabilir.
        Gate::define('access-admin', function (User $user) {
            return in_array($user->role, ['founder','admin', 'author', 'user'], true);
        });

        // 2. ÖZEL KAPI: Sadece en üst yetkili adminin yapacağı işlemler için
        Gate::define('is-admin', function (User $user) {
            return in_array($user->role, ['founder', 'admin'], true);
        });

        // 3. ÖZEL KAPI: Genel yazar erişim kontrolü (Örn: Yeni yazı ekleme sayfası)
        Gate::define('is-author', function (User $user) {
            return in_array($user->role, ['founder', 'admin', 'author'], true);
        });

        // 4. İÇERİK KAPISI: Yazarın sadece kendi yazısını düzenleyebilmesi/silebilmesi için
        Gate::define('manage-post', function (User $user, $post) {
            // Kurucu veya admin ise her yazıyı düzenleyip silebilir, direkt içeri alalım.
            if (in_array($user->role, ['founder', 'admin'], true)) {
                return true;
            }

            // Eğer kullanıcı yazar ise, sadece yazının sahibi oysa (id'ler eşleşiyorsa) izin verelim.
            if ($user->role === 'author') {
                return $user->id === $post->user_id; 
            }

            // Standart üyeler veya diğer durumlar için kapıyı kapalı tutalım.
            return false;
        });

    }
}