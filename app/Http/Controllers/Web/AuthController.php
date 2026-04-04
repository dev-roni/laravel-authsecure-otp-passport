<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Services\SmsService;
use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;

class AuthController extends Controller
{
    //registration view
    public function registration(){
        return view('registration');
    }

    //store user data in cache
    public function register(UserRegistrationRequest $request,SmsService $message){
        $validdata= $request->validated();

        $phone = $validdata['phone'];
        $otp   = random_int(100000, 999999);
        Cache::put('userData', $validdata, now()->addMinutes(10));
        Cache::put('otp', $otp, now()->addMinutes(10));

        $message->send($phone, $otp);

        return redirect()->route('otp.view');
    }

    //otp view
    public function otp(){
        if(Cache::has('userData') && Cache::has('otp')){
            return view('otp');
        }
        else{
            return back();
        }
    }

    //otp check and finaly store user data 
    public function otpStore(Request $request){
        $otp = Cache::get('otp');
        $userData = Cache::get('userData');
        if($otp === $request->otp){
            // User তৈরি করুন
            User::create($userData);

            // Cache মুছে দিন
            Cache::forget('otp');
            Cache::forget('userData');

            return view('login');
        }
        return back()->withErrors(['otp' => 'OTP সঠিক হয়নি']);
    }

    //login view
    public function login(){
        return view('login');
    }
}
