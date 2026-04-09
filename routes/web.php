<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\SocialAuthController;

Route::get('/',[AuthController::class,'registration'])->name('registration');
Route::post('registration',[AuthController::class,'register'])->name('register');
Route::get('otp',[AuthController::class,'otp'])->name('otp.view');
Route::post('otp',[AuthController::class,'otpCheck'])->name('otp');
Route::post('resend',[AuthController::class,'resendOtp'])->name('resend.otp');
Route::get('login',[AuthController::class,'login'])->name('login');
Route::post('login',[AuthController::class,'logedIn'])->name('login.success');
Route::get('forgot-password',[AuthController::class,'forgotPasswordView'])->name('forgot.password');
Route::post('forgot-password',[AuthController::class,'forgotPassword'])->name('token.sent');
Route::get('/reset-password/{token}', [AuthController::class, 'resetPasswordView'])->name('password.reset');
Route::post('reset-password',[AuthController::class,'resetPassword'])->name('password.update');

Route::get('success',[AuthController::class,'success'])->name('success');

// Google o oath2
Route::get('/auth/google',          [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Facebook o oath2
Route::get('/auth/facebook',          [SocialAuthController::class, 'redirectToFacebook'])->name('auth.facebook');
Route::get('/auth/facebook/callback', [SocialAuthController::class, 'handleFacebookCallback'])->name('auth.facebook.callback');
