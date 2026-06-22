<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AuthController;


Route::get('assets/metrics', [AssetController::class, 'getDashboardMetrics']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::put('/user', [AuthController::class, 'updateProfile']);

    Route::put('/user/password', [AuthController::class, 'changePassword']);

    Route::apiResource('assets', AssetController::class);
});