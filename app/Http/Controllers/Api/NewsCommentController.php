<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsComment;
use Illuminate\Http\Request;

class NewsCommentController extends Controller
{
    public function store(Request $request, $newsId)
    {
        $request->validate(['body' => 'required|string']);

        $news = News::findOrFail($newsId);

        $comment = NewsComment::create([
            'news_id' => $news->id,
            'user_id' => $request->user()->id,
            'body'    => $request->body
        ]);

        // Load data user agar frontend bisa langsung menampilkan nama/avatar
        $comment->load('user');

        return response()->json([
            'message' => 'Komentar terkirim',
            'data' => $comment
        ], 201);
    }

    public function destroy(Request $request, $id)
    {
        $comment = NewsComment::findOrFail($id);

        // Hanya Pemilik Komentar atau Admin yang boleh hapus
        if ($comment->user_id !== $request->user()->id && $request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete();
        return response()->json(['message' => 'Komentar dihapus']);
    }
}