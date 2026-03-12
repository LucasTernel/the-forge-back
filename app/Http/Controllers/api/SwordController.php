<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sword;
use App\Models\Collection;

class SwordController extends Controller
{
    public function index(Request $request)
    {
        $query = Sword::with(['era', 'origin', 'collection', 'media', 'criteria']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('era_id')) {
            $query->where('era_id', $request->era_id);
        }

        if ($request->filled('origin_id')) {
            $query->where('origin_id', $request->origin_id);
        }

        $items = $query->orderBy('name')->get();
        return response()->json($items, 200, ['Content-Type' => 'application/json; charset=UTF-8']);
    }

    public function show($id)
    {
        $item = Sword::with(['era', 'origin', 'collection.user', 'media', 'criteria', 'comments.user', 'likes'])->find($id);

        if (!$item) {
            return response()->json(['Mauvaise épée, il serait temps de mieux trancher.'], 404, ['Content-Type' => 'application/json; charset=UTF-8']);
        }

        return response()->json($item, 200, ['Content-Type' => 'application/json; charset=UTF-8']);
    }

    public function store(Request $request)
    {
        if (!auth('sanctum')->check()) {
            return response()->json(['message' => 'Veuillez vous connecter (go to login)'], 401, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
        }

        $user = auth('sanctum')->user();

        // One user = his collection id
        $collection = $user->collections()->first();

        if (!$collection) {
            return response()->json(['message' => "Vous ne possédez aucune collection. Veuillez en créer une d'abord."], 404, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
        }

        $data = $request->except('image_cover');
        $data['collection_id'] = $collection->id;

        $sword = Sword::create($data);

        // image_cover : fichier uploadé OU URL string
        $sword->image_cover = $this->handleImageCover($request, $collection->id, $sword->id);
        $sword->save();

        return response()->json($sword, 201);
    }

    public function update(Request $request, $id)
    {
        $sword = Sword::with('collection')->find($id);

        if (!$sword) {
            return response()->json(['message' => 'Épée non trouvée.'], 404);
        }

        if (!$sword->collection || $sword->collection->user_id !== auth()->id()) {
            return response()->json(['message' => 'Action non autorisée. Seul le bon épéiste de cette collection peut modifier cette épée.'], 403, ['Content-Type' => 'application/json; charset=UTF-8']);
        }

        $data = $request->except(['id', 'collection_id', 'created_at', 'updated_at', 'image_cover']);

        // image_cover : fichier uploadé OU URL string
        $imageCover = $this->handleImageCover($request, $sword->collection_id, $sword->id);
        if ($imageCover !== null) {
            $data['image_cover'] = $imageCover;
        }

        $sword->update($data);

        return response()->json($sword, 200, ['Content-Type' => 'application/json; charset=UTF-8']);
    }

    public function destroy(Request $request, $id)
    {
        $sword = Sword::with('collection')->find($id);

        if (!$sword) {
            return response()->json(['message' => 'Épée non trouvée.'], 404);
        }

        if (!$sword->collection || $sword->collection->user_id !== auth()->id()) {
            return response()->json(['message' => "Action non autorisée. Seul le bon épéiste de cette collection peut supprimer cette épée."], 403, ['Content-Type' => 'application/json; charset=UTF-8']);
        }

        $sword->delete();

        return response()->json(["message" => "L'épée a été retirée de la collection."], 200, ['Content-Type' => 'application/json; charset=UTF-8']);
    }

    /**
     * Gère image_cover :
     *   - fichier  → stocké dans /storage/{collection_id}/{sword_id}/
     *   - URL/path → stocké tel quel
     *   - absent   → null
     */
    private function handleImageCover(Request $request, $collectionId, $swordId): ?string
    {
        if ($request->hasFile('image_cover')) {
            $file = $request->file('image_cover');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9.\-_]/', '', $file->getClientOriginalName());
            $path = $file->storeAs("{$collectionId}/{$swordId}", $filename, 'public');
            return '/storage/' . $path;
        }

        if ($request->filled('image_cover')) {
            return $request->input('image_cover');
        }

        return null;
    }
}