<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AuthController;

// 🔓 1. இந்த ரூட்டுக்கு டோக்கன் தேவையில்லை (Public Route)
Route::post('/login', [AuthController::class, 'login'])->name('login');

// 🔒 2. இந்த குரூப்புக்குள்ள இருக்குற ரூட்ஸ்க்கு கண்டிப்பா டோக்கன் வேணும் (Protected Routes)
Route::middleware('auth:sanctum')->group(function () {
    
    // 💡 இப்போ தான் நாம Flutter-க்காக /api/user ரூட்டை ஆட் பண்ணியிருக்கோம்!
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // உங்க அஸெட்ஸ் CRUD ஆபரேஷன்ஸ் எல்லாமே டோக்கன் பாதுகாப்புக்கு உள்ளே வந்துடும்
    Route::apiResource('assets', AssetController::class);
});