<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Like;

class LikeController extends Controller
{
    public function toggle(Request $request, $id)
    {
        $user = $request->user();
        $like = Like::where('user_id', $user->id)->where('sword_id', $id)->first();

        if ($like) {
            $like->delete();
            return response()->json(['message' => 'Like supprimé', 'liked' => false]);
        }

        Like::create([
            'user_id' => $user->id,
            'sword_id' => $id,
        ]);

        return response()->json(['message' => 'Épée likée !', 'liked' => true], 201);
    }
}