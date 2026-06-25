<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SeoService;
use App\Models\Post; // Blog yerine Post modelini dahil ettik
use App\Models\Category; // Kategori modelini de buraya ekledik dostum
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BlogController extends Controller
{
    protected $seoService;

    // SeoService bağımlılığını constructor içinde almaya devam ediyoruz
    public function __construct(SeoService $seoService)
    {
        $this->seoService = $seoService;
    }

    // 1. SEO Açıklama Üretme Fonksiyonu
    public function generateDescription(Request $request)
    {
        $request->validate(['content' => 'required|string']);
        $description = $this->seoService->generateMetaDescription($request->content);
        return response()->json(['description' => $description]);
    }


    // 2. Blog Listeleme Sayfası
    public function index(Request $request)
    {
        // Kategori ilişkisini 'with' kullanarak önden yüklüyoruz (N+1 sorgu problemini engellemek için)
        $query = Post::query()->with('category');

        // Veritabanından ayarları çekiyoruz
        $paginationLimit = \App\Models\Setting::getVal('pagination_limit', 7);


        // 1. Arama Filtresi (Hem Başlıkta Hem İçerikte Arar)
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            
            // Sorguyu grupluyoruz ki status ve is_featured filtreleriyle çakışmasın
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('content', 'like', '%' . $searchTerm . '%');
            });
        }

        // Kategori Filtresi (Kategorisiz seçeneği eklendi)
        if ($request->filled('category_id')) {
            if ($request->category_id === 'none') {
                $query->whereNull('category_id'); // Kategorisi olmayanları getirir
            } else {
                $query->where('category_id', $request->category_id);
            }
        }

        // 2. Öne Çıkarılma Durumu Filtresi
        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->is_featured);
        }

        // 3. Sıralama Filtresi
        $sortBy = $request->get('sort_by', 'created_at'); 
        $sortDirection = $request->get('sort_direction', 'desc'); 

        if (in_array($sortBy, ['title', 'created_at', 'status', 'is_featured'])) {
            $query->orderBy($sortBy, $sortDirection);
        } else {
            $query->latest();
        }

        // 4. Listeleme Mantığı
        if ($request->query('all') == 1) {
            $blogs = $query->get();
        } else {
            $blogs = $query->paginate($paginationLimit)->withQueryString(); 
        }

        // Blade'deki select dropdown doldurmak için tüm kategorileri çekiyoruz
        $categories = Category::orderBy('name', 'asc')->get();

        return view('admin.blogs.index', compact('blogs', 'categories'));
    }

    // 3. Toplu İşlem Fonksiyonu
    public function bulkAction(Request $request)
    {
        Gate::authorize('is-admin');

        $action = $request->input('action');
        $ids = $request->input('ids'); 

        if (empty($ids)) {
            return redirect()->back()->with('toast', __('Please select at least one blog!'));
        }

        if ($action === 'delete') {
            Post::whereIn('id', $ids)->delete();
            return redirect()->back()->with('toast', __('Selected blogs deleted successfully.'));
        }

        if ($action === 'publish') {
            Post::whereIn('id', $ids)->update(['status' => 'publish']); 
            return redirect()->back()->with('toast', __('Selected blogs published successfully.'));
        }

        if ($action === 'draft') {
            Post::whereIn('id', $ids)->update(['status' => 'draft']);
            return redirect()->back()->with('toast', __('Selected blogs returned to draft successfully.'));
        }

        // Toplu Öne Çıkarma İşlemi (Yeni)
        if ($action === 'feature') {
            Post::whereIn('id', $ids)->update(['is_featured' => 'featured']);
            return redirect()->back()->with('toast', __('Selected blogs featured successfully.'));
        }

        // Toplu Normale Döndürme İşlemi (Yeni)
        if ($action === 'unfeature') {
            Post::whereIn('id', $ids)->update(['is_featured' => 'normal']);
            return redirect()->back()->with('toast', __('Selected blogs removed from featured status.'));
        }

        return redirect()->back();
    }
}