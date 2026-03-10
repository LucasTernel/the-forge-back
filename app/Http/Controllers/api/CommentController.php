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
    public function show($id)
    {
        $comment = Comment::find($id);
        if(!$comment)
            {
            return response()->json(['message' => 'type not found'], 404, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
            }

            return response() -> json($comment,200,['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
    }
}