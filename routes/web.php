<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;

Route::get('/',[AuthController::class,'registration'])->name('registration');
Route::post('registration',[AuthController::class,'register'])->name('register');
Route::get('otp',[AuthController::class,'otp'])->name('otp.view');
Route::post('otp',[AuthController::class,'otpCheck'])->name('otp');
Route::post('resend',[AuthController::class,'resendOtp'])->name('resend.otp');
Route::get('login',[AuthController::class,'login'])->name('login');
Route::post('login',[AuthController::class,'logedIn'])->name('login.success');
