<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Sword;

class LikeController extends Controller
{
    public function toggle($id)
    {
        $user = auth()->user();
        $sword = Sword::findOrFail($id);

        $like = Like::where('user_id', $user->id)
                    ->where('sword_id', $sword->id)
                    ->first();

        if ($like) {
            $like->delete();
            return response()->json([
                'message' => 'Sword unliked',
                'liked' => false
            ]);
        }

        Like::create([
            'user_id' => $user->id,
            'sword_id' => $sword->id
        ]);

        return response()->json([
            'message' => 'Sword liked',
            'liked' => true
        ]);
    }
}