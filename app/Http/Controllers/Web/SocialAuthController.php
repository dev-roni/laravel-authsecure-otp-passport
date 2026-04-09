<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SocialAuthController extends Controller
{
    public function redirectToGoogle(){
        return Socialite::driver('google')->stateless()->redirect(); // ✅
    }

    public function handleGoogleCallBack(){
        try{
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $googleUser->getAvatar(),
                ]);
            } else {
                $user = User::create([
                    'name'      => $googleUser->getName(),
                    'email'     => $googleUser->getEmail(),
                    'avatar'    => $googleUser->getAvatar(),
                    'google_id' => $googleUser->getId(),
                ]);
            }

            $user = User::updateOrCreate(
                ['google_id' => $googleUser->getId()],
                [
                    'name'=>$googleUser->getName(),
                    'email'=> $googleUser->getEmail(),
                    'avatar'=> $googleUser->getAvatar(),
                    'google_id'=>$googleUser->getId(),
                ]
            );

            Auth::login($user);
            return redirect()->route('success');
        }
        catch(Exception $e){
            return redirect()->route('login')->withErrors();
            
        }
    }
}
