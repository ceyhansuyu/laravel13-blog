<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MediaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\SecurityController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Admin\CommentadminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdsettingController;
use App\Http\Controllers\Admin\UserController;

use App\Http\Controllers\SitemapController;

// Google'ın doğrudan erişebileceği sitemap rotası
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// --- Auth Gerektirmeyen (Ziyaretçilere Açık) Rotalar ---

Route::get('/', [PostController::class, 'index'])->name('home');

Route::get('/posts/{post}/preview', [PostController::class, 'getPreview'])->name('posts.preview');

// Listeleme için (GET)
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

// Yeni post oluşturma/kaydetme için (POST)
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');







Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');

// --- Auth Gerektiren (Yönetim) Rotalar ---
Route::middleware('auth','can:access-admin')->group(function () {
    
    Route::resource('posts', PostController::class)->except(['index', 'show'])->middleware('can:is-author');
    
    // Media Routes
    Route::get('/media', [MediaController::class, 'index'])->name('media.index')->middleware('can:is-author');
    Route::post('/media', [MediaController::class, 'store'])->name('media.store')->middleware('can:is-author');
    Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy')->middleware('can:is-admin');


    // --- Tüm Admin Paneli Rotaları ---
    // Bu grup sayesinde URL'lerin başına otomatik 'admin/', isimlerin başına 'admin.' eklenir.
    Route::prefix('admin')->name('admin.')->group(function () {

        Route::resource('pages', \App\Http\Controllers\Admin\PageController::class)->middleware('can:is-admin');




        // Kategori CRUD işlemleri (Ekleme, Düzenleme, Silme)
        Route::resource('categories', CategoryController::class)->except(['create', 'show'])->middleware('can:is-admin');

        // Settings Routes
        Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit')->middleware('can:is-admin');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update')->middleware('can:is-admin');
        Route::post('/settings/clear-cache', [SettingsController::class, 'clearCache'])->name('settings.clear-cache')->middleware('can:is-admin');
        Route::post('/settings/optimize', [SettingsController::class, 'optimize'])->name('settings.optimize')->middleware('can:is-admin');


        // Ad Settings Routes
        Route::get('/ads', [AdsettingController::class, 'index'])->name('ads.index')->middleware('can:is-admin');
        Route::put('/ads', [AdsettingController::class, 'update'])->name('ads.update')->middleware('can:is-admin');


        // Users Settings Routes
        /*
        Route::get('/users', [AdsettingController::class, 'index'])->name('users.index');
        Route::put('/users', [AdsettingController::class, 'update'])->name('users.update');
        */
        Route::resource('users', UserController::class)->middleware('can:is-admin');
        
        // Profil Rotaları
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile'); 
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit'); 
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Güvenlik Ayarları Rotaları
        Route::get('/security', [SecurityController::class, 'edit'])->name('security.edit')->middleware('can:is-admin');
        Route::post('/security/settings', [SecurityController::class, 'updateSettings'])->name('security.settings.update')->middleware('can:is-admin'); 
        Route::post('/security/clear-logs', [SecurityController::class, 'clearLogs'])->name('security.clear-logs')->middleware('can:is-admin');

        // Blog Routes
        Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index')->middleware('can:is-admin');
        Route::post('/blogs/bulk-action', [BlogController::class, 'bulkAction'])->name('blogs.bulk-action')->middleware('can:is-admin');

        // Comment Routes
        Route::get('/comments', [CommentadminController::class, 'index'])->name('comments.index')->middleware('can:is-admin');
        Route::post('/comments/bulk-action', [CommentadminController::class, 'bulkAction'])->name('comments.bulk-action')->middleware('can:is-admin');
        
    });
});



Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // Güvenlik Sayfası Listeleme Rotası
    Route::get('/admin/security', [SecurityController::class, 'index'])->name('security.index');
    
    // Senin formundaki POST action karşılığı olan rota:
    Route::post('/admin/security/clear-logs', [SecurityController::class, 'clearLogs'])->name('security.clear-logs');
});

Route::get('/post/{blog}/{slug}', [PostController::class, 'show'])->name('post.show');

Route::post('/blog/generate-description', [BlogController::class, 'generateDescription'])
    ->name('admin.blog.generate-description');



// --- DİKKAT: Çakışmayı Önleyen Kısım ---
// Jokerli ({slug}) veya genel rotalar her zaman EN ALTTA olmalıdır, yoksa "edit" gibi kelimeleri yutar!
Route::get('posts/{post}/{slug}', [PostController::class, 'show'])->name('posts.show');
Route::get('posts/{post}', [PostController::class, 'show']); // Sadece id ile show istenirse diye burada


// Kategori ziyaret rotalarını buraya taşıdık
Route::get('/c/{id}/{slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/c/uncategorized', [CategoryController::class, 'showUncategorized'])->name('categories.show-uncategorized');

Route::get('/admin/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified','can:is-author'])->name('dashboard');


// Dinamik Sayfalar Rotası (Örn: site.com/page/gizlilik-politikasi)
Route::get('/page/{slug}', function($slug) {
    // Sadece aktif olan sayfayı bul, bulamazsa 404 ver
    $page = \App\Models\Page::where('slug', $slug)->where('is_active', true)->firstOrFail();
    return view('posts.showpage', compact('page'));
})->name('posts.showpage');



use App\Models\Media;

Route::get('/test-media', function () {
    return response()->json(Media::latest()->get());
});


Route::middleware(['throttle:admin-login'])->group(function () {
    Route::post('/admin/login', [LoginController::class, 'login']);
});





require __DIR__.'/auth.php';