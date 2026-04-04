<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function registration(){
        return view('registration');
    }

    public function otp(UserRegistrationRequest $request){

    }
}
