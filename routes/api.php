<?php

use App\Http\Controllers\api\SwordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\CollectionController;
use App\Http\Controllers\api\MediaController;
use App\Http\Controllers\api\CriteriaController;
use App\Http\Controllers\api\TypeController;
use App\Http\Controllers\api\OriginController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\FollowController;

Route::post('/register', [AuthController::class , 'register']);
Route::post('/login', [AuthController::class , 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/media', [MediaController::class , 'store']);
    Route::post('/users/{id}/follow', [FollowController::class, 'toggleFollow']);
    Route::get('/user', function (Request $request) {
            return $request->user();
        }
        
        );
        Route::get('/logout', [AuthController::class , 'logout']);
    });

Route::get('/swords', [SwordController::class , 'index']);
Route::post('/swords', [SwordController::class , 'store']);
Route::get('/swords/{id}', [SwordController::class , 'show']);
Route::put('/swords/{id}', [SwordController::class , 'update'])->middleware('auth:sanctum');

Route::get('/collection/{id}', [CollectionController::class , 'show']);
Route::get('/collections', [CollectionController::class , 'index']);
Route::get('/collections/{id}', [CollectionController::class , 'show']);

<<<<<<< HEAD
Route::get('/origins', [OriginController::class , 'index']);
Route::get('/origins/{id}', [OriginController::class , 'show']);

Route::get('/criterias', [CriteriaController::class , 'index']);

Route::post('/media', [MediaController::class, 'store'])->middleware('auth:sanctum');

=======
<<<<<<< HEAD
>>>>>>> 58d1ade (again)


Route::get('/types', [TypeController::class , 'index']);
Route::get('/types/{id}', [TypeController::class , 'show']);
<<<<<<< HEAD

Route::get('/users/{id}/followers', [FollowController::class, 'getFollowers']);
Route::get('/users/{id}/following', [FollowController::class, 'getFollowing']);
=======
=======
Route::get('/origins', [OriginController::class , 'index']);
Route::get('/origins/{id}', [OriginController::class , 'show']);

Route::get('/criterias', [CriteriaController::class , 'index']);

Route::post('/media', [MediaController::class, 'store'])->middleware('auth:sanctum');
>>>>>>> 90cfade (add road of items and collection v2)
>>>>>>> 58d1ade (again)