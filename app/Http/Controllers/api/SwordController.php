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
}
