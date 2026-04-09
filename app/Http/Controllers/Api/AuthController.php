<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\PasswordResetMail;
use App\Mail\OtpMail;
use App\Services\SmsService;
use App\Http\Requests\UserRegistrationRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use Laravel\Socialite\Facades\Socialite;
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
        Mail::to($email)->queue(new OtpMail($otp));

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

    // Login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user  = Auth::user();
            $token = $user->createToken('auth_token')->accessToken;

            return response()->json([
                'status' => true,
                'token'  => $token,
                'user'   => $user,
            ], 200);
        }

        return response()->json([
            'status'  => false,
            'message' => 'Email or Password is incorrect',
        ], 401);
    }

    // Logged in user
    public function user(Request $request)
    {
        return response()->json([
            'status' => true,
            'user'   => $request->user(),
        ], 200);
    }

     // Logout
    public function logout(Request $request)
    {
        //$request->user()->token()->revoke();
        $request->user()->token()->delete();
        return response()->json([
            'status'  => true,
            'message' => 'Logout successfuly',
        ], 200);
    }

    //forgot password request
    public function forgotPassword(ForgotPasswordRequest $request)
    {

        $token = Str::random(6);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token'      => bcrypt($token),
                'created_at' => now(),
            ]
        );

        $url = url('/reset-password/' . $token . '?email=' . $request->email);
        Mail::to($request->email)->send(new PasswordResetMail($url,$token,$request->email));

        return response()->json([
            'status'  => true,
            'message' => 'Password reset token পাঠানো হয়েছে',
        ], 200);

    }

    //reset password
    public function resetPassword(ResetPasswordRequest $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|',
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

    public function redirectToGoogle()
    {
        $url = Socialite::driver('google')
                        ->stateless() // ✅ API তে stateless লাগবে
                        ->redirect()
                        ->getTargetUrl();

        return response()->json([
            'status' => true,
            'url'    => $url,
        ]);
    }
}
