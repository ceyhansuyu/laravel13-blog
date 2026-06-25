<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stevebauman\Purify\Facades\Purify;
use App\Models\PostView;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(\Illuminate\Http\Request $request)
    {
        // Veritabanından ayarları çekiyoruz
        $paginationLimit = \App\Models\Setting::getVal('pagination_limit', 7);
        $searchFields = \App\Models\Setting::getVal('search_fields', ['title', 'slug', 'content']);
        $enableSearch = \App\Models\Setting::getVal('enable_search', true);

        // Girişleri güvenli hale getirmek için trim ve temizlik yapıyoruz
        // Eğer arama kapalıysa ($enableSearch false ise) direkt null set ediyoruz
        $search = ($enableSearch && request('search')) ? strip_tags(trim(request('search'))) : null;
        
        $categoryId = request('category');
        $status = request('status');
        $authorId = request('author'); // Yazar ID'sini alıyoruz
        
        // Input validasyonu
        if ($categoryId && $categoryId !== 'uncategorized' && !is_numeric($categoryId)) {
            $categoryId = null;
        }
        
        if ($status && !in_array($status, ['draft', 'publish'])) {
            $status = null;
        }

        // Yazar verisini hazırlıyoruz (Gelen ID'nin güvenlik açısından numeric olmasını kontrol ediyoruz)
        $author = null;
        if ($authorId && is_numeric($authorId)) {
            $author = \App\Models\User::find($authorId);
        }
        
        $posts = Post::with('category')
            // Önce 'featured' olanları en başa alıyoruz.
            ->orderByRaw("CASE WHEN is_featured = 'featured' THEN 0 ELSE 1 END")
            // Ardından kendi içlerinde tarihe göre sıralanmaya devam ederler
            ->latest() 
            // Login olmayan kullanıcılar sadece "publish" yazıları görsün
            ->when(!auth()->check(), function($query) {
                $query->where('status', 'publish');
            })
            // Yazar filtreleme (Yeni eklenen kısım)
            ->when($authorId && is_numeric($authorId), function($query) use ($authorId) {
                $query->where('user_id', (int)$authorId);
            })
            // Kategori filtreleme
            ->when($categoryId, function($query) use ($categoryId) {
                if ($categoryId === 'uncategorized') {
                    $query->whereNull('category_id');
                } else {
                    $query->where('category_id', (int)$categoryId);
                }
            })
            // Status filtreleme
            ->when($status, function($query) use ($status) {
                $query->where('status', $status);
            })
            // Arama işlemi (enableSearch false ise veya search boşsa burası bypass edilir)
            ->when($enableSearch && $search, function($query) use ($search, $searchFields) {
                $query->where(function ($q) use ($search, $searchFields) {
                    foreach ($searchFields as $index => $field) {
                        if ($index === 0) {
                            $q->where($field, 'like', '%' . $search . '%');
                        } else {
                            $q->orWhere($field, 'like', '%' . $search . '%');
                        }
                    }
                });
            })
            ->paginate((int) $paginationLimit);

        $categories = Category::all();
        
        // $author değişkenini de compact içine ekleyerek Blade'e gönderiyoruz
        return view('posts.index', compact('posts', 'search', 'enableSearch', 'categoryId', 'status', 'categories', 'author'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge([
            'slug' => Str::slug($request->title),
        ]);

        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required|unique:posts,slug|max:255',
            'content' => 'required',
            'status' => 'required|in:draft,publish',
            'category_id' => 'nullable|exists:categories,id',
            'is_featured'  => 'required|in:normal,featured',
            'meta_description' => 'nullable|string|max:500', // Validasyonu unutma
        ]);

        // Meta Descriptionu XSS'temizle
        $validatedData['meta_description'] = Purify::clean($validatedData['meta_description']);

        // Başlığı XSS'ten temizliyoruz
        $validatedData['title'] = Purify::clean($validatedData['title']);

        // KODUNUN ÇALIŞAN KISMI: İçeriği XSS'ten temizliyoruz
        $validatedData['content'] = Purify::clean($validatedData['content']);

        // YENİ EKKLENEN KISIM: Yazıyı yazan kullanıcının ID'sini ekliyoruz
        $validatedData['user_id'] = auth()->id();

        $post = Post::create($validatedData);

        // Hem 'success' hem de Toast componentinin okuyabilmesi için 'toast' session verisini gönderiyoruz
        return redirect()->route('posts.show', ['post' => $post->id, 'slug' => $post->slug])
            ->with('success', __('Post created successfully.'))
            ->with('toast', __('Post created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post, Request $request)
    {
        $sessionId = $request->getSession()->getId();

        // Ziyaretçinin bu oturumda bu yazıyı okuyup okumadığını kontrol ediyoruz
        $viewed = PostView::where('post_id', $post->id)
                          ->where('session_id', $sessionId)
                          ->exists();

        // Eğer okumadıysa...
        if (!$viewed) {
            // 1. Log tablosuna ekle
            PostView::create([
                'post_id'    => $post->id,
                // 'user_id' => $post->user_id, 
                'session_id' => $sessionId,
                'ip_address' => $request->ip(),
            ]);

            // 2. Sayaç tablosunu (posts) artır
            // timestamps özelliğini geçici olarak false yapıyoruz
            $post->timestamps = false;
            $post->increment('view_count');
            // İşlem bitince tekrar true yapıyoruz (diğer işlemler için)
            $post->timestamps = true;




        }

        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        Gate::authorize('manage-post', $post);
        $categories = \App\Models\Category::all();
        return view('posts.edit', compact('post', 'categories'));

    }

/**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        Gate::authorize('manage-post', $post);

        $request->merge([
            'slug' => Str::slug($request->title),
        ]);

        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required|unique:posts,slug,' . $post->id . '|max:255',
            'content' => 'required',
            'status' => 'required|in:draft,publish',
            'category_id' => 'nullable|exists:categories,id',
            'is_featured'  => 'required|in:normal,featured',
            'meta_description' => 'nullable|string|max:500', // Validasyonu unutma

        ]);

        // Meta Descriptionu XSS'temizle
        $validatedData['meta_description'] = Purify::clean($validatedData['meta_description']);

        // Başlığı XSS'ten temizliyoruz
        $validatedData['title'] = Purify::clean($validatedData['title']);

        // Güncelleme sırasında da içeriği XSS'ten temizliyoruz
        $validatedData['content'] = Purify::clean($validatedData['content']);

        $post->update($validatedData);

        // Güncelleme işleminden sonra iki session değerini de basıyoruz
        return redirect()->route('posts.show', ['post' => $post->id, 'slug' => $post->slug])
            ->with('success', __('Post updated successfully.'))
            ->with('toast', __('Post updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        Gate::authorize('manage-post', $post);
        $post->delete();
        
        // Silme işleminden sonra iki session değerini de gönderiyoruz
        return redirect()->route('posts.index')
            ->with('success', __('Post deleted successfully.'))
            ->with('toast', __('Post deleted successfully.'));
    }

    /**
     * Get post preview for tooltip
     */

    public function getPreview(Post $post)
    {
        $content = $post->content;

        // 1. Liste öğelerini (<li>) özel bir işaretle değiştir (böylece birbirine yapışmazlar)
        $content = preg_replace('/<li[^>]*>(.*?)<\/li>/i', "\n• $1", $content);
        
        // 2. <pre> veya <code> bloklarını tamamen temizle
        $content = preg_replace('/<(pre|code|blockquote)\b[^>]*>([\s\S]*?)<\/\1>/i', '', $content);

        // 3. HTML etiketlerini temizle
        $plainText = strip_tags($content);
        
        // 4. Entity'leri ( &lt; gibi) çöz
        $plainText = htmlspecialchars_decode($plainText);

        // 5. Satır başlarını koruyarak temizle
        // \n olan yerlerdeki boşlukları düzenle, ama satırları öldürme
        $plainText = preg_replace('/[ \t]+/u', ' ', $plainText);
        
        // 6. Kelime bazlı özet (senin şık yöntem)
        $words = explode(' ', trim($plainText));
        $summary = implode(' ', array_slice($words, 0, 30)); // Tooltip için 30 kelime idealdir
        
        if (count($words) > 30) {
            $summary .= ' ...';
        }

        return response()->json([
            'content' => $summary, // Bu artık maddeleri • işaretiyle ayıracak
            'created_at' => $post->created_at->translatedFormat('d M, Y'),
            'created_at_human' => $post->created_at->diffForHumans(),
        ]);
    }
}