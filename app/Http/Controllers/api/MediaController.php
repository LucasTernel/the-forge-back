<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Media;
use App\Models\Sword;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'sword_id' => 'required|exists:swords,id',
            'type' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $sword = Sword::with('collection')->find($request->sword_id);

        if (!$sword) {
            return response()->json(['message' => 'L épée est introuvable...'], 404, ['Content-Type' => 'application/json; charset=UTF-8']);
        }

        if (!$sword->collection || $sword->collection->user_id !== auth()->id()) {
            return response()->json(['message' => 'Action non autorisée. Seul le bon épéiste de cette collection peut modifier cette épée.'], 403, ['Content-Type' => 'application/json; charset=UTF-8']);
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();

            $file->storeAs("{$sword->collection_id}/{$sword->id}", $filename, 'public');

            $media = Media::create([
                'sword_id' => $sword->id,
                'type' => $request->type,
                'url' => $filename,
            ]);

            return response()->json($media, 201, ['Content-Type' => 'application/json; charset=UTF-8']);
        }

        return response()->json(['message' => 'Aucune image envoyée.'], 400, ['Content-Type' => 'application/json; charset=UTF-8']);
    }
}