<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SwordController extends Controller
{
    public function index()
    {
        $items = \App\Models\Sword::with(['type', 'origin', 'collection', 'media', 'criteria'])->get();
        return response()->json($items);
    }

    public function show($id)
    {
        $item = \App\Models\Sword::with(['type', 'origin', 'collection', 'media', 'criteria'])->find($id);
        
        if (!$item) {
            return response()->json(['Mauvaise épée, il serait temps de mieux trancher.'], 404);
        }

        return response()->json($item);
    }

    public function store(Request $request)
    {
        $swords = \App\Models\Sword::create($request->all());
        return response()->json($swords);
    }

    public function update(Request $request, $id)
    {
        $swords = \App\Models\Sword::with('')->findOrFail($id);
        $swords->update($request->all());
        return response()->json($swords);
    }

    public function destroy($id)
    {
        $swords = \App\Models\Sword::with('')->findOrFail($id);
        $swords->delete();
        return response()->json(null);
    }

}
