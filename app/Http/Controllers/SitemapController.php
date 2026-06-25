<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        // Sorguya 'id' sütununu da dahil ettik dostum
        $posts = Post::where('status', 'publish')
            ->select('id', 'slug', 'updated_at') 
            ->latest()
            ->get();

        return response()
            ->view('sitemap', compact('posts'))
            ->header('Content-Type', 'text/xml');
    }
}