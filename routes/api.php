<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['error' => '404'], 404);
});

Route::middleware('api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/reset-password', 'App\Http\Controllers\AuthController@resetPassword')->withoutMiddleware(['api']);
        Route::post('/register', 'App\Http\Controllers\AuthController@register')->withoutMiddleware(['api']);
        Route::post('/login', 'App\Http\Controllers\AuthController@login')->withoutMiddleware(['api']);
        Route::post('/change-password/{token}', 'App\Http\Controllers\AuthController@changePassword')->withoutMiddleware(['api']);
        Route::post('/change-password', 'App\Http\Controllers\AuthController@changePassword');
        Route::get('/me', 'App\Http\Controllers\AuthController@me');
        Route::get('/logout', 'App\Http\Controllers\AuthController@logout');
        Route::get('/refresh', 'App\Http\Controllers\AuthController@refresh');
    });

    Route::prefix('accounts')->group(function () {
        Route::get('/', 'App\Http\Controllers\AccountController@accounts');
        Route::get('/{id}', 'App\Http\Controllers\AccountController@account');
        Route::post('/', 'App\Http\Controllers\AccountController@create');
        Route::put('/{id}', 'App\Http\Controllers\AccountController@update');
        Route::delete('/{id}', 'App\Http\Controllers\AccountController@delete');
        Route::post('/set-default/{id}', 'App\Http\Controllers\AccountController@defineDefaultAccount');
    });
});
