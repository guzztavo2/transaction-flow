<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['error' => '404'], 404);
});

Route::middleware(['api'])->prefix('auth')->group(function () {
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->withoutMiddleware(['api']);
    Route::post('/register', [AuthController::class, 'register'])->withoutMiddleware(['api']);
    Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware(['api']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/refresh', [AuthController::class, 'refresh']);
});
