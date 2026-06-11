<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AuthController;


Route::get('assets/metrics', [AssetController::class, 'getDashboardMetrics']);
Route::apiResource('assets', AssetController::class);
// 🔓 1. இந்த ரூட்டுகளுக்கு டோக்கன் தேவையில்லை (Public Routes)
Route::post('/login', [AuthController::class, 'login'])->name('login');

// 💡 ஃப்ளட்டருக்காக புதிய ரெஜிஸ்டர் மற்றும் ஃபர்காட் பாஸ்வேர்ட் ரூட்கள் இங்கே சேர்க்கப்பட்டுள்ளது!
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

// 🔒 2. இந்த குரூப்புக்குள்ள இருக்குற ரூட்ஸ்க்கு கண்டிப்பா டோக்கன் வேணும் (Protected Routes)
Route::middleware('auth:sanctum')->group(function () {
    
    // Flutter-க்காக /api/user ரூட்
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // உங்க அஸெட்ஸ் CRUD ஆபரேஷன்ஸ்
    Route::apiResource('assets', AssetController::class);
});