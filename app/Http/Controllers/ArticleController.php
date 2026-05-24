<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with('comments')->where('is_published', true)->latest();

        if ($request->filled('category') && $request->category !== 'All') {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title_en', 'LIKE', "%{$search}%")
                  ->orWhere('title_hi', 'LIKE', "%{$search}%")
                  ->orWhere('excerpt_en', 'LIKE', "%{$search}%")
                  ->orWhere('excerpt_hi', 'LIKE', "%{$search}%")
                  ->orWhere('content_en', 'LIKE', "%{$search}%")
                  ->orWhere('content_hi', 'LIKE', "%{$search}%");
            });
        }

        $categories = Article::where('is_published', true)->distinct()->pluck('category');

        return view('articles.index', [
            'articles' => $query->paginate(30)->withQueryString(),
            'categories' => $categories,
            'filters' => $request->only(['category', 'search']),
        ]);
    }

    public function show(Article $article)
    {
        $article->load('comments');

        return view('articles.show', [
            'article' => $article,
        ]);
    }
}
