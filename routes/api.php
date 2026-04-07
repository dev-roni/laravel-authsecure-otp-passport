<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/otp', [AuthController::class, 'otpCheck']);
Route::post('/resendOtp', [AuthController::class, 'resendOtp']);
Route::post('/login',      [AuthController::class, 'login']);

//must login
Route::middleware('auth:api')->group(function () {
    Route::get('/user',    [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
});