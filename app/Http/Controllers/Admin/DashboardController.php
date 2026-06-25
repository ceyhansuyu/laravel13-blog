<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Media; // Kullanıyorsan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        // Temel istatistikler
        $totalPosts = Post::count();
        $totalViews = Post::sum('view_count'); // Yazılarında views sütunu varsa roket gibi toplar
        
        // Yorum ve Medya istatistikleri
        $pendingComments = Comment::where('status', 'pending')->count(); 
        $totalMedia = Media::count(); 

        // Son 5 blog yazısı (Yayınlananlar veya taslaklar karışık en yeniler)
        $recentPosts = Post::latest()->take(5)->get();


        // stevebauman/purify paketinin aktif olup olmadığını kontrol et
        $isPurifierActive = app()->bound('purify');


        // Önbellek durumunu kontrol et (bootstrap/cache/config.php var mı bakar)
        $isCached = app()->configurationIsCached();

        return view('admin.dashboard', compact(
            'totalPosts',
            'totalViews',
            'pendingComments',
            'totalMedia',
            'recentPosts',
            'isPurifierActive',
            'isCached'
        ));
    }
}