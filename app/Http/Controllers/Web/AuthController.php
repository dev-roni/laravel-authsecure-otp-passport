<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\OtpMail;
use App\Services\SmsService;
use App\Http\Requests\UserRegistrationRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
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
            return redirect()->route('registration');
        }
    }

    //otp check and finaly store user data 
    public function otpCheck(Request $request){

        $otp = Cache::get('otp');
        $userData = Cache::get('userData');

        if($otp && $otp == $request->otp){
            // User তৈরি করুন
            User::create($userData);

            // Cache মুছে দিন
            Cache::forget('otp');
            Cache::forget('userData');

            return redirect()->route('login')->with('success','user registered successfully');
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
            return view('success')->with('user',auth()->user());
        }

        return back()->withInput()->withErrors(['error' => 'email or password is incorrect']);
    }

    //forgot password view
    public function forgotPasswordView(){
        return view('forgotPassword');
    }

    //forgot  password
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        // Token তৈরি করে email পাঠায়
        $status = Password::sendResetLink(
            $request->only('email')
        );

        $email = $request->email;
        if ($status === Password::RESET_LINK_SENT) {
            return view('success',compact('email'));
        }

        return back()->with('error','email not send');
    }

    // Reset Password View
    public function resetPasswordView(Request $request, string $token)
    {
        return view('reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    } 

    //reset password
    public function resetPassword(ResetPasswordRequest $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|max:20',
        ]);

        //  Token মিলিয়ে password update করে
        $status = Password::reset(
            $request->only('email', 'password', 'token'),
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success','Password update successfully');
        }

        return back()->withErrors(['token' => 'Token invalid']);
    }
}
