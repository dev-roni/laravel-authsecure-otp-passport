<?php

namespace App\Http\Controllers\web;

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
    //registration view
    public function registration(){
        return view('registration');
    }

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

        return redirect()->route('otp.view');
    }

    //otp view
    public function otp(){
        if(Cache::has('userData') && Cache::get('otp')){
            return view('otp');
        }
        else{
            return back();
        }
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

            return redirect()->route('otp.view');
        }
        return redirect()->route('registration');
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

        return back()->withErrors(['error' => 'email or password is incorrect']);
    }

    //password reset
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        // Token তৈরি করে email পাঠায়
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return view();
        }

        return back()->with('error','email not found');
    }

    //reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        //  Token মিলিয়ে password update করে
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'status'  => true,
                'message' => 'Password সফলভাবে পরিবর্তন হয়েছে',
            ], 200);
        }

        return response()->json([
            'status'  => false,
            'message' => 'Token সঠিক নয় বা মেয়াদ শেষ',
        ], 400);
    }
}
