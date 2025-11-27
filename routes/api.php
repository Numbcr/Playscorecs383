<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Session check endpoint
Route::get('/session-check', [AuthController::class, 'sessionCheck']);

// Auth endpoints (used for AJAX/JSON requests)
Route::post('/auth/login', [AuthController::class, 'login'])->withoutMiddleware('verified');
Route::post('/auth/register', [AuthController::class, 'register'])->withoutMiddleware('verified');
Route::post('/auth/logout', [AuthController::class, 'logout'])->withoutMiddleware('verified');

// Game endpoints
Route::get('/games/popular', [GameController::class, 'getPopular']);
Route::get('/games/recent', [GameController::class, 'getRecent']);
Route::get('/games/search', [GameController::class, 'search']);
Route::get('/games/{id}', [GameController::class, 'show']);
Route::get('/games/rawg/key', [GameController::class, 'getRawgApiKey']);

// Protected game endpoints (admin only)
Route::middleware(['auth', EnsureUserIsAdmin::class])->group(function () {
    Route::post('/games', [GameController::class, 'store']);
    Route::put('/games/{id}', [GameController::class, 'update']);
    Route::delete('/games/{id}', [GameController::class, 'destroy']);
});
