<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sword;
use App\Models\Collection;

class SwordController extends Controller
{
    public function index()
    {
        $items = Sword::with(['type', 'origin', 'collection', 'media', 'criteria'])->get();
        return response()->json($items, 200, ['Content-Type' => 'application/json; charset=UTF-8']);
    }

    public function show($id)
    {
        $item = Sword::with(['type', 'origin', 'collection.user', 'media', 'criteria', 'comments.user', 'likes'])->find($id);

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
            return response()->json(['message' => 'Vous ne possédez aucune collection. Veuillez en créer une d\'abord.'], 404, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
        }

        $data = $request->all();
        $data['collection_id'] = $collection->id;

        if ($request->hasFile('image_cover')) {
            unset($data['image_cover']);
        }

        $sword = Sword::create($data);

        if ($request->hasFile('image_cover')) {
            $file = $request->file('image_cover');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9.\-_]/', '', $file->getClientOriginalName());
            $path = $file->storeAs("{$collection->id}/{$sword->id}", $filename, 'public');
            $sword->image_cover = '/storage/' . $path;
            $sword->save();
        }

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

        $data = $request->except(['id', 'collection_id', 'created_at', 'updated_at']);

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
            return response()->json(['message' => 'Action non autorisée. Seul le bon épéiste de cette collection peut supprimer cette épée.'], 403, ['Content-Type' => 'application/json; charset=UTF-8']);
        }

        $sword->delete();

        return response()->json(['message' => 'L\'épée a été retirée de la collection.'], 200, ['Content-Type' => 'application/json; charset=UTF-8']);
    }
}