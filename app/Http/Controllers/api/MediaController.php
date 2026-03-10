<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function store(Request $request)
    {
        if (!auth('sanctum')->check()) {
            return response()->json(['message' => 'Veuillez vous connecter (go to login)'], 401, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
        }

        $request->validate([
            'sword_id' => 'required|exists:swords,id',
        ]);

        $sword = \App\Models\Sword::findOrFail($request->sword_id);
        $collection = \App\Models\Collection::find($sword->collection_id);
        $user = auth('sanctum')->user();

        if ($collection->user_id !== $user->id) {
            return response()->json(['message' => 'Cette collection ne vous appartient pas'], 403, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
        }

        $data = $request->except('url');

        if ($request->hasFile('url')) {
            $file = $request->file('url');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9.\-_]/', '', $file->getClientOriginalName());
            $path = $file->storeAs("{$collection->id}/{$sword->id}", $filename, 'public');
            $data['url'] = '/storage/' . $path;
        }
        elseif ($request->filled('url')) {
            $data['url'] = $request->input('url');
        }

        $media = \App\Models\Media::create($data);
        return response()->json($media, 201);
    }
}