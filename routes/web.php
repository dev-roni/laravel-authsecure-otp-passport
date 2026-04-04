<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;

Route::get('/',[AuthController::class,'registration'])->name('registration');
Route::get('otp',[AuthController::class,'otp'])->name('otp');
