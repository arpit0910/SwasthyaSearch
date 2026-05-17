<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleCommentController extends Controller
{
    public function store(Request $request, Article $article)
    {
        $validated = $request->validate([
            'user_name' => 'required|string|max:255',
            'comment' => 'required|string|max:1000',
        ]);

        $comment = $article->comments()->create([
            'user_name' => trim($validated['user_name']),
            'comment' => trim($validated['comment']),
            'is_approved' => true, // Auto-approve for seamless user experience
        ]);

        return response()->json([
            'success' => true,
            'comment' => $comment,
        ]);
    }
}
