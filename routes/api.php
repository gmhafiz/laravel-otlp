<?php

use App\Http\Controllers\ExternalController;
use App\Http\Controllers\Metrics\MetricsController;
use App\Http\Controllers\SendEmailController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/metrics', [MetricsController::class, 'index']);

Route::resource('users', UserController::class); // <-- add this line and its imports
Route::resource('email', SendEmailController::class);
Route::resource('external', ExternalController::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
