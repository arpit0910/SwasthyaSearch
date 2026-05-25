<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with('comments')->where('is_published', true)->latest();

        // Filter by category - support multiple selections
        $categories = $request->input('category', []);
        if (!is_array($categories)) {
            $categories = ($categories && $categories !== 'All') ? [$categories] : [];
        }
        $categories = array_filter($categories); // Remove empty values

        if (!empty($categories)) {
            $query->whereIn('category', $categories);
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

        $categoryOptions = Article::where('is_published', true)->distinct()->pluck('category');

        return view('articles.index', [
            'articles' => $query->paginate(30)->withQueryString(),
            'categories' => $categoryOptions,
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
