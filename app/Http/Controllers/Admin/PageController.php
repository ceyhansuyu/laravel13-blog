<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::latest()->paginate(10);
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        Gate::authorize('is-admin');
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('is-admin');
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'content' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // is_active checkbox'tan gelmiyorsa varsayılan olarak false (0) yapıyoruz
        $validated['is_active'] = $request->has('is_active');

        Page::create($validated);

        return redirect()->route('admin.pages.index')->with('toast', __('Page created successfully.'));
    }

    public function edit(Page $page)
    {
        Gate::authorize('is-admin');
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        Gate::authorize('is-admin');
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,' . $page->id,
            'content' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        // Eğer kullanıcı slug alanını boş bıraktıysa başlıktan otomatik üretelim
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $page->update($validated);

        return redirect()->route('admin.pages.index')->with('toast', __('Page updated successfully.'));
    }

    public function destroy(Page $page)
    {
        Gate::authorize('is-admin');
        $page->delete();
        return redirect()->route('admin.pages.index')->with('toast',  __('Page deleted successfully.'));
    }
}