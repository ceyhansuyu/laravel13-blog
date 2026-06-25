<?php

namespace App\Http\Controllers\Admin; 

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentadminController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::with('post'); // Yazı ilişkisini (post) çekiyoruz ki n+1 problemi olmasın.

        // Veritabanından ayarları çekiyoruz
        $paginationLimit = \App\Models\Setting::getVal('pagination_limit', 7);

        // Arama Filtresi 
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('content', 'like', '%' . $request->search . '%')
                ->orWhere('name', 'like', '%' . $request->search . '%')
                // Post tablosundaki yazı isminde (title) arama yapıyoruz
                ->orWhereHas('post', function($postQuery) use ($request) {
                    $postQuery->where('title', 'like', '%' . $request->search . '%');
                });
            });
        }

        // Durum Filtresi
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sıralama
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDir);

        // Tümü veya Sayfalamalı
        if ($request->get('all') == 1) {
            $comments = $query->get();
        } else {
            $comments = $query->paginate($paginationLimit)->withQueryString();
        }

        return view('admin.comments.index', compact('comments'));
    }

    public function bulkAction(Request $request)
    {
        Gate::authorize('is-admin');

        $request->validate([
            'ids' => 'required|array',
            'action' => 'required|string',
        ]);

        $ids = $request->ids;

        switch ($request->action) {
            case 'delete':
                Comment::whereIn('id', $ids)->delete();
                $message = __('Selected comments have been successfully deleted.');
                break;
            case 'approve':
                Comment::whereIn('id', $ids)->update(['status' => 'approved']);
                $message = __('Selected comments have been successfully approved.');
                break;
            case 'pending':
                Comment::whereIn('id', $ids)->update(['status' => 'pending']);
                $message = __('Selected comments have been moved to pending.');
                break;
            default:
                return back()->with('error', __('An invalid action was selected.'));
        }

        return redirect()->back()->with('toast', __($message));
    }
}