<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        $url = Socialite::driver('google')
                        ->stateless() 
                        ->redirect()
                        ->getTargetUrl();

        return response()->json([
            'status' => true,
            'url'    => $url,
        ]);
    }

    //  Google Callback 
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')
                                   ->stateless()
                                   ->user();

            $user = User::updateOrCreate(
                ['google_id' => $googleUser->getId()],
                [
                    'name'      => $googleUser->getName(),
                    'email'     => $googleUser->getEmail(),
                    'avatar'    => $googleUser->getAvatar(),
                    'google_id' => $googleUser->getId(),
                ]
            );

            //  Passport Token 
            $token = $user->createToken('auth_token')->accessToken;

            return response()->json([
                'status' => true,
                'token'  => $token,
                'user'   => $user,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Google login ব্যর্থ হয়েছে',
                'error'   => $e->getMessage(),
            ], 400);
        }
    }

    // Facebook Redirect URL দিন
    public function redirectToFacebook()
    {
        $url = Socialite::driver('facebook')
                        ->stateless()
                        ->redirect()
                        ->getTargetUrl();

        return response()->json([
            'status' => true,
            'url'    => $url,
        ]);
    }

    // Facebook Callback — Token দিন
    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')
                                     ->stateless()
                                     ->user();

            $user = User::updateOrCreate(
                ['facebook_id' => $facebookUser->getId()],
                [
                    'name'        => $facebookUser->getName(),
                    'email'       => $facebookUser->getEmail(),
                    'avatar'      => $facebookUser->getAvatar(),
                    'facebook_id' => $facebookUser->getId(),
                ]
            );

            // Passport Token দিন
            $token = $user->createToken('auth_token')->accessToken;

            return response()->json([
                'status' => true,
                'token'  => $token,
                'user'   => $user,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Facebook login failed',
                'error'   => $e->getMessage(),
            ], 400);
        }
    }
}
