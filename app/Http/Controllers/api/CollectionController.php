<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = \App\Models\Collection::with(['user', 'swords'])->get();
        return response()->json($collections);
    }

    public function show($id)
    {
        $collection = \App\Models\Collection::with(['user', 'swords'])->find($id);

        if (!$collection) {
            return response()->json(['Mauvaise collection d épée, tranche mieux.'], 404);
        }

        return response()->json($collection);
    }
}
