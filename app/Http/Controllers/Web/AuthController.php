<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
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
        $otp = Cache::get('otp');
        if(Cache::has('userData') && $otp){
            return view('otp',['otp'=>$otp]);
        }
        else{
            return back();
        }
    }

    //resend otp
    public function resendOtp(SmsService $message){
        $userData =Cache::get('userData');
        if($userData){
            $phone = $userData['phone'];
            $otp   = random_int(100000, 999999);
            Cache::put('otp', $otp, now()->addMinutes(10));

            $message->send($phone, $otp);

            return redirect()->route('otp.view');
        }
        return redirect()->route('registration');
    }

    //otp check and finaly store user data 
    public function otpCheck(Request $request){
        $otp = Cache::get('otp');
        $userData = Cache::get('userData');

        if($otp == $request->otp){
            // User তৈরি করুন
            User::create($userData);

            // Cache মুছে দিন
            Cache::forget('otp');
            Cache::forget('userData');

            return redirect()->route('login');
        }
        return back()->withErrors(['otp' => 'OTP IS WRONG']);
    }

    //login view
    public function login(){
        return view('login');
    }

    //loged In
    public function logedIn(Request $request)
    {
        $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return 'login successfull';
        }

        return back()->withErrors(['phone' => 'email or password is incorrect']);
    }
}
