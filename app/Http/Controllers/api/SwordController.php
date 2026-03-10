<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sword;

class SwordController extends Controller
{
    public function index()
    {
        $items = Sword::with(['type', 'origin', 'collection', 'media', 'criteria'])->get();
        return response()->json($items, 200, ['Content-Type' => 'application/json; charset=UTF-8']);
    }

    public function show($id)
    {
        $item = Sword::with(['type', 'origin', 'collection', 'media', 'criteria'])->find($id);

        if (!$item) {
            return response()->json(['Mauvaise épée, il serait temps de mieux trancher.'], 404, ['Content-Type' => 'application/json; charset=UTF-8']);
        }

        return response()->json($item, 200, ['Content-Type' => 'application/json; charset=UTF-8']);
    }

    public function store(Request $request)
    {
        $swords = Sword::create($request->all());
        return response()->json($swords);
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

}