<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['error' => '404'], 404);
});

Route::middleware(['api', 'checkUserSession'])->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/reset-password', 'App\Http\Controllers\AuthController@resetPassword')->withoutMiddleware(['api', 'checkUserSession']);
        Route::post('/register', 'App\Http\Controllers\AuthController@register')->withoutMiddleware(['api', 'checkUserSession']);
        Route::post('/login', 'App\Http\Controllers\AuthController@login')->withoutMiddleware(['api', 'checkUserSession']);
        Route::post('/change-password/{token}', 'App\Http\Controllers\AuthController@changePassword')->withoutMiddleware(['api', 'checkUserSession']);
        Route::post('/change-password', 'App\Http\Controllers\AuthController@changePassword');
        Route::get('/me', 'App\Http\Controllers\AuthController@me');
        Route::get('/logout', 'App\Http\Controllers\AuthController@logout');
        Route::get('/refresh', 'App\Http\Controllers\AuthController@refresh');
    });

    Route::prefix('accounts')->group(function () {
        Route::get('/', 'App\Http\Controllers\AccountController@index');
        Route::get('/{id}', 'App\Http\Controllers\AccountController@show');
        Route::post('/', 'App\Http\Controllers\AccountController@store');
        Route::put('/{id}', 'App\Http\Controllers\AccountController@update');
        Route::delete('/{id}', 'App\Http\Controllers\AccountController@delete');
        Route::post('/define-dedault/{id}', 'App\Http\Controllers\AccountController@defineDefault');
    });

    Route::prefix('transactions')->group(function () {
        Route::post('/', 'App\Http\Controllers\TransactionController@store');
    });
});
