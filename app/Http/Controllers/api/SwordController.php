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
        return response()->json($items, 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
    }

    public function show($id)
    {
        $item = Sword::with(['type', 'origin', 'collection', 'media', 'criteria'])->find($id);

        if (!$item) {
            return response()->json(['Mauvaise épée, il serait temps de mieux trancher.'], 404, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
        }

        return response()->json($item, 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
    }

    public function store(Request $request)
    {
        $swords = Sword::create($request->all());
        return response()->json($swords);
    }

    public function update(Request $request, $id)
    {
        $swords = Sword::findOrFail($id);
        $swords->update($request->all());
        return response()->json($swords);
    }

    public function destroy($id)
    {
        $swords = Sword::findOrFail($id);
        $swords->delete();
        return response()->json(null);
    }

}