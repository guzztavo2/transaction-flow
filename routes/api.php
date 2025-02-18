<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(["error" => "404"], 404);
});
