<?php

use App\Http\Controllers\api\CommentController;
use App\Http\Controllers\api\SwordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\CollectionController;
use App\Http\Controllers\api\MediaController;
use App\Http\Controllers\api\CriteriaController;
use App\Http\Controllers\api\EraController;
use App\Http\Controllers\api\OriginController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\FollowController;
use App\Http\Controllers\api\FeedController;
use App\Http\Controllers\api\LikeController;

Route::post('/register', [AuthController::class , 'register']);
Route::post('/login', [AuthController::class , 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/feed/swords', [FeedController::class , 'swordsFeed']);
    Route::get('/feed/collections', [FeedController::class , 'collectionsFeed']);
    Route::post('/media', [MediaController::class , 'store']);
    Route::delete('/media/{id}', [MediaController::class , 'destroy']);
    Route::post('/swords', [SwordController::class , 'store']);
    Route::put('/swords/{id}', [SwordController::class , 'update']);
    Route::delete('/swords/{id}', [SwordController::class , 'destroy']);
    Route::post('/swords/{id}/like', [LikeController::class , 'toggle']);
    Route::post('/swords/{id}/comments', [CommentController::class , 'store']);
    Route::put('/comments/{id}', [CommentController::class , 'update']);
    Route::delete('/comments/{id}', [CommentController::class , 'destroy']);
    Route::post('/users/{id}/follow', [FollowController::class , 'toggleFollow']);
    Route::put('/collections/{id}', [CollectionController::class , 'update']);
    Route::post('/eras', [EraController::class , 'store']);
    Route::put('/eras/{id}', [EraController::class , 'update']);
    Route::delete('/eras/{id}', [EraController::class , 'destroy']);
    Route::put('/origins/{id}', [OriginController::class , 'update']);
    Route::delete('/origins/{id}', [OriginController::class , 'destroy']);
    Route::get('/logout', [AuthController::class , 'logout']);
    Route::get('/user', function (Request $request) {
            return $request->user();
        }
        );
    });

Route::get('/swords', [SwordController::class , 'index']);
Route::get('/swords/{id}', [SwordController::class , 'show']);

Route::get('/collection/{id}', [CollectionController::class , 'show']);
Route::get('/collections', [CollectionController::class , 'index']);
Route::get('/collections/{id}', [CollectionController::class , 'show']);

Route::get('/origins', [OriginController::class , 'index']);
Route::get('/origins/{id}', [OriginController::class , 'show']);

Route::get('/eras', [EraController::class , 'index']);
Route::get('/eras/{id}', [EraController::class , 'show']);

Route::get('/swords/{swordId}/comments', [CommentController::class , 'show']);
Route::get('/criterias', [CriteriaController::class , 'index']);

Route::get('/users/{id}/followers', [FollowController::class , 'getFollowers']);
Route::get('/users/{id}/following', [FollowController::class , 'getFollowing']);