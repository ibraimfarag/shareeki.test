<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\SocialProcess\SocialAccountService;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function redirectToSocial($social)
    {
        return Socialite::with($social)->redirect();
    }

    function handleSocialCallback(SocialAccountService $service, $social)
    {        
        try {
            $user = $service->setOrGetUser(Socialite::driver($social)->stateless());
            auth()->login($user);
            

            if(session()->has('post')){
                return redirect()->to('new_post');
            }else{
                return redirect()->route('home');
            }

        } catch (\Exception $e) {
            return $e;
        }
    }
}