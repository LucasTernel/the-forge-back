<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Criteria;

class CriteriaController extends Controller
{
    public function index()
    {
        $criterias = Criteria::with('swords')->get();
        return response()->json($criterias, 200, ['Content-Type' => 'application/json; charset=UTF-8']);
    }
}
