<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Origin;

class OriginController extends Controller
{
    public function index()
    {
        $origins = Origin::with('swords')->get();
        return response()->json($origins, 200, ['Content-Type' => 'application/json; charset=UTF-8']);
    }

    public function show($id)
    {
        $origin = Origin::with('swords')->find($id);

        if (!$origin) {
            return response()->json(['message' => 'L origine de ton épée est introuvable...'], 404, ['Content-Type' => 'application/json; charset=UTF-8']);
        }

        return response()->json($origin, 200, ['Content-Type' => 'application/json; charset=UTF-8']);
    }
}
