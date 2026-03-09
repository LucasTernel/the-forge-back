<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\SwordController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route :: get('/Sword', [SwordController::class, 'index']);
Route :: get('/Sword/{id}', [SwordController::class, 'show']);
Route :: post('/Sword', [SwordController::class, 'store']);
Route :: put('/Sword/{id}', [SwordController::class, 'update']);
Route :: delete('/Sword/{id}', [SwordController::class, 'destroy']);
