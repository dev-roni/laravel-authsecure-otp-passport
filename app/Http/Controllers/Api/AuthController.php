<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use App\Services\SmsService;
use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;

class AuthController extends Controller
{
    //store user data in cache
    public function register(UserRegistrationRequest $request,SmsService $message){
        
        $validdata= $request->validated();
        $otp   = random_int(100000, 999999);

        Cache::put('userData', $validdata, now()->addMinutes(10));
        Cache::put('otp', $otp, now()->addMinutes(10));
        
        //otp send by email
        $email = $validdata['email'];
        Mail::to($email)->send(new OtpMail($otp));

        //otp send by phone
        // $phone = $validdata['phone'];
        // $message->send($phone, $otp);

        return response()->json([
            'status'  => true,
            'message' => 'OTP পাঠানো হয়েছে',
        ], 200);
    }

    //otp check and finaly store user data 
    public function otpCheck(Request $request){

        $otp = Cache::get('otp');
        $userData = Cache::get('userData');

        //cache check
        if (!$userData || !$otp) {
            return response()->json([
                'status'  => false,
                'message' => 'Session শেষ হয়ে গেছে, আবার চেষ্টা করুন',
            ], 401);
        }

        if($otp == $request->otp){
            // User তৈরি করুন
            $user = User::create($userData);

            //create tocken
            $token = $user->createToken('auth_token')->accessToken;

            // Cache মুছে দিন
            Cache::forget('otp');
            Cache::forget('userData');

            return response()->json([
                'status' => true,
                'token'  => $token,
                'user'   => $user,
            ], 201);
        }
        return response()->json([
            'status'  => false,
            'message' => 'OTP সঠিক নয়',
        ], 401);
    }

    //resend otp
    public function resendOtp(SmsService $message){

        $userData =Cache::get('userData');
        $otp   = random_int(100000, 999999);

        if($userData){
            Cache::put('otp', $otp, now()->addMinutes(10));

            //send otp by email
            $email = $userData['email'];
            Mail::to($email)->send(new OtpMail($otp));
            
            //send otp by phone
            // $phone = $userData['phone'];
            // $message->send($phone, $otp);

            return response()->json([
                'status'  => true,
                'message' => 'OTP পাঠানো হয়েছে',
            ], 200);
        }
        return response()->json([
            'status'  => false,
            'message' => 'OTP পাঠানো যায়নি',
        ], 401);
    }
}
