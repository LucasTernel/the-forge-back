<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Sword;

class FeedController extends Controller
{
    public function swordsFeed(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json(['message' => 'Veuillez vous connecter.'], 401, ['Content-Type' => 'application/json; charset=UTF-8']);
        }

        $followingIds = $user->following()->pluck('users.id');

        if ($followingIds->isEmpty()) {
            return response()->json([], 200, ['Content-Type' => 'application/json; charset=UTF-8']);
        }

        $swords = Sword::with(['era', 'origin', 'collection.user', 'media', 'criteria'])
            ->whereHas('collection', function ($query) use ($followingIds) {
            $query->whereIn('user_id', $followingIds);
        })
            ->latest()
            ->get();

        return response()->json($swords, 200, ['Content-Type' => 'application/json; charset=UTF-8']);
    }

    public function collectionsFeed(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json(['message' => 'Veuillez vous connecter.'], 401, ['Content-Type' => 'application/json; charset=UTF-8']);
        }

        $followingIds = $user->following()->pluck('users.id');

        if ($followingIds->isEmpty()) {
            return response()->json([], 200, ['Content-Type' => 'application/json; charset=UTF-8']);
        }

        $collections = \App\Models\Collection::with(['user', 'swords'])
            ->whereIn('user_id', $followingIds)
            ->latest()
            ->get();

        return response()->json($collections, 200, ['Content-Type' => 'application/json; charset=UTF-8']);
    }
}