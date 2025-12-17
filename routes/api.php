<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HikingTrailController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\CommunityController;
use App\Http\Controllers\Api\NewsController;


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
use App\Http\Controllers\Api\DashboardController;

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
});