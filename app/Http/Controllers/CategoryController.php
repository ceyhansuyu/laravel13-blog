<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('posts')->latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function edit(Category $category)
    {
        Gate::authorize('is-admin');
        return view('admin.categories.edit', compact('category'));
    }

    public function store(Request $request)
    {
        Gate::authorize('is-admin');
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name'
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', __('Category created successfully.'))
            ->with('toast', __('Category created successfully.'));
    }

    public function update(Request $request, Category $category)
    {
        Gate::authorize('is-admin');
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', __('Category updated successfully.'))
            ->with('toast', __('Category updated successfully.'));
    }

    public function destroy(Category $category)
    {
        Gate::authorize('is-admin');
        // Kategoriye ait yazılar varsa silmeyi engelleyebiliriz veya yazıları kategorisiz bırakırız. (migration'da nullOnDelete yaptık)
        $category->delete();
        return redirect()->route('admin.categories.index')
            ->with('success', __('Category deleted successfully.'))
            ->with('toast', __('Category deleted successfully.'));
    }

    public function show(int $id, string $slug)
    {
        // Kategori verisini al
        $category = Category::findOrFail($id);

        // Kategoriye ait yazıları, ana sayfadaki gibi sayfalama ile çek
        // 'posts' ilişkisini modelinde tanımladığını varsayıyorum

        // Güvenlik/Tip kontrolü: Veritabanından gelen ayarın integer olduğundan emin oluyoruz.
        $paginationLimit = (int) \App\Models\Setting::getVal('pagination_limit', 7);
        if ($paginationLimit <= 0) {
            $paginationLimit = 7;
        }

        $posts = $category->posts()
            // Önce 'featured' olanları en başa alıyoruz.
            ->orderByRaw("CASE WHEN is_featured = 'featured' THEN 0 ELSE 1 END")
            ->latest()
            // Login olmayan kullanıcılar sadece "publish" yazıları görsün
            ->when(!auth()->check(), function($query) {
                $query->where('status', 'publish');
            })
            ->paginate($paginationLimit);

        $categories = Category::all();

        // 'index' sayfasının görünümünü kullanıyoruz, sadece veri setini kategoriye göre kısıtlıyoruz
        return view('posts.index', [
            'posts' => $posts,
            'enableSearch' => false, // Kategori sayfasında arama kutusunu kapatmak istersen
            'categoryId' => $id,
            'search' => null,        
            'status' => null,
            'categories' => $categories
        ]);
    }

    public function showUncategorized()
    {
        // Güvenlik/Tip kontrolü: Veritabanından gelen ayarın integer olduğundan emin oluyoruz.
        $paginationLimit = (int) \App\Models\Setting::getVal('pagination_limit', 7);
        if ($paginationLimit <= 0) {
            $paginationLimit = 7;
        }

        // Kategori ID'si NULL olanları çekiyoruz
        $posts = \App\Models\Post::whereNull('category_id')
            ->orderByRaw("CASE WHEN is_featured = 'featured' THEN 0 ELSE 1 END")
            ->latest()
            ->when(!auth()->check(), function($query) {
                $query->where('status', 'publish');
            })
            ->paginate($paginationLimit);

        $categories = Category::all();

        // Kategori varmış gibi gönderiyoruz, isim olarak da "Uncategorized" veriyoruz
        $category = (object) ['name' => __('Uncategorized')];

        return view('posts.index', [
            'posts' => $posts,
            'category' => $category,
            'enableSearch' => false, 
            'search' => null,        
            'categoryId' => null,    
            'status' => null,        
            'categories' => $categories
        ]);
    }
}