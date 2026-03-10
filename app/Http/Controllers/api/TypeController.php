<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Type;


class TypeController extends Controller
{
    public function index()
    {
        $types =Type::all();
        return response()->json($types, 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
    }
    public function show($id) 
       {
        $type = Type::find($id);
        if (!$type)
            {
                return response()->json(['message' => 'type not found'], 404, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
            }

            return response()->json($type, 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);

    }

}
