<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Collection;

class CollectionController extends Controller
{
    public function index(Request $request)
    {
        $query = Collection::with(['user', 'swords']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $collections = $query->orderBy('name')->get();
        return response()->json($collections, 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
    }

    public function show($id)
    {
        $collection = Collection::with(['user', 'swords'])->find($id);

        if (!$collection) {
            return response()->json(["Mauvaise collection d'épée, tranche mieux."], 404, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
        }

        return response()->json($collection, 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
    }

    public function update(Request $request, $id)
    {
        $collection = Collection::findOrFail($id);

        if ($collection->user_id !== auth()->id()) {
            return response()->json(['message' => 'Cette collection ne vous appartient pas'], 403, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
        ]);

        if ($request->has('name')) {
            $collection->name = $request->name;
        }

        // image_cover : fichier uploadé OU URL string
        if ($request->hasFile('image_cover')) {
            $file = $request->file('image_cover');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9.\-_]/', '', $file->getClientOriginalName());
            $path = $file->storeAs("{$collection->id}/cover", $filename, 'public');
            $collection->image_cover = '/storage/' . $path;
        }
        elseif ($request->filled('image_cover')) {
            // URL externe ou chemin /storage/... déjà formé
            $collection->image_cover = $request->input('image_cover');
        }

        $collection->save();

        return response()->json($collection, 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
    }
}