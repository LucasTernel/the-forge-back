<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller

{

    public function index()
    {
        $comments = Comment::all();
        return response()->json($comments, 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
    }
    public function show($swordId)
    {
        $comments = Comment::where('sword_id', $swordId)->get();
        if ($comments->isEmpty())
            {
            return response()->json(['message' => 'comments not found for sword'], 404, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
            }

            return response() -> json($comments,200,['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
    }
}
