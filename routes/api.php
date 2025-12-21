<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HikingTrailController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\CommunityController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\OpenTripController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\DashboardController;

// --- ROUTE PUBLIC (Tidak butuh Token) ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/mountains', [HikingTrailController::class, 'index']);
Route::get('/mountains/{slug}', [HikingTrailController::class, 'show']);
Route::get('/home', [HomeController::class, 'index']);
Route::get('/community', [CommunityController::class, 'index']);
Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/{slug}', [NewsController::class, 'show']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/galleries', [GalleryController::class, 'index']);
Route::post('/galleries/{id}/comment', [GalleryController::class, 'storeComment']);
Route::post('/galleries/{id}/like', [GalleryController::class, 'toggleLike']);
Route::get('/trips', [OpenTripController::class, 'index']);

// --- ROUTE PRIVATE (Harus Login / Punya Token) ---
Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/mountains', [HikingTrailController::class, 'store']);
    Route::put('/mountains/{id}', [HikingTrailController::class, 'update']);
    Route::delete('/mountains/{id}', [HikingTrailController::class, 'destroy']);
    Route::get('/admin/stats', [DashboardController::class, 'index']);
    Route::post('/news', [NewsController::class, 'store']);
    Route::put('/news/{id}', [NewsController::class, 'update']);
    Route::delete('/news/{id}', [NewsController::class, 'destroy']);
    Route::post('/open-trips', [OpenTripController::class, 'store']);
    Route::get('/my-trips', [OpenTripController::class, 'myTrips']);
    Route::put('/open-trips/{id}', [OpenTripController::class, 'update']);
    Route::delete('/open-trips/{id}', [OpenTripController::class, 'destroy']);
    Route::post('/open-trips/{id}/join', [OpenTripController::class, 'join']);
    Route::get('/open-trips/{id}/participants', [OpenTripController::class, 'getParticipants']);
    Route::get('/joined-trips', [OpenTripController::class, 'joinedTrips']);
    Route::delete('/open-trips/{tripId}/participants/{userId}', [OpenTripController::class, 'removeParticipant']);
    Route::post('/open-trips/{id}/leave', [OpenTripController::class, 'leave']);
    Route::post('/galleries', [GalleryController::class, 'store']);
    Route::delete('/galleries/{id}', [GalleryController::class, 'destroy']);
    Route::post('/galleries/{id}/comments', [GalleryController::class, 'storeComment']);
    Route::post('/galleries/{id}/like', [GalleryController::class, 'toggleLike']);
});