<?php

use App\Http\Controllers\api\SwordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\CollectionController;
use App\Http\Controllers\api\CriteriaController;
use App\Http\Controllers\api\TypeController;
use App\Http\Controllers\api\OriginController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/swords', [SwordController::class , 'index']);
Route::get('/swords/{id}', [SwordController::class , 'show']);

Route::get('/collection/{id}', [CollectionController::class , 'show']);
Route::get('/collections', [CollectionController::class , 'index']);
Route::get('/collections/{id}', [CollectionController::class , 'show']);



Route ::get('/types', [TypeController::class , 'index']);
Route ::get('/types/{id}', [TypeController::class , 'show']);