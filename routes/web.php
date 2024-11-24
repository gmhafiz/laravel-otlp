<?php

use App\Http\Controllers\Metrics\MetricsController;
use Illuminate\Support\Facades\Route;

Route::get('/metrics', [MetricsController::class, 'index']);

Route::get('/', function () {
    return view('welcome');
});
