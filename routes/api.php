<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\TripController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::apiResource('cars', CarController::class)
    ->only([
        'index', 'store', 'show', 'destroy'
    ])
    ->parameters([
        'car' => 'carId'
    ]);

Route::apiResource('trips', TripController::class)->only([
    'index', 'store'
]);
