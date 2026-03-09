<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SwordController extends Controller
{
    public function index()
    {
        $swords = \App\Models\Sword::all();
        return response()->json($swords);
    }

    public function show($id)
    {
        $swords = \App\Models\Sword::with('colis.livraison')->findOrFail($id);
        return response()->json($swords);
    }

    public function store(Request $request)
    {
        $swords = \App\Models\Sword::create($request->all());
        return response()->json($swords);
    }

    public function update(Request $request, $id)
    {
        $swords = \App\Models\Sword::with('colis.livraison')->findOrFail($id);
        $swords->update($request->all());
        return response()->json($swords);
    }

    public function destroy($id)
    {
        $swords = \App\Models\Sword::with('colis.livraison')->findOrFail($id);
        $swords->delete();
        return response()->json(null);
    }

}