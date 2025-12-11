<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HikingTrailController;
use App\Http\Controllers\Api\HomeController;

// --- ROUTE PUBLIC (Tidak butuh Token) ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/mountains', [HikingTrailController::class, 'index']);
Route::get('/mountains/{slug}', [HikingTrailController::class, 'show']);
Route::get('/home', [HomeController::class, 'index']);

// --- ROUTE PRIVATE (Harus Login / Punya Token) ---
Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/user', [AuthController::class, 'me']);
    
});