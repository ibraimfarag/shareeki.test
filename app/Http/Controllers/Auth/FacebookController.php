<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use http\Message;
use Illuminate\Http\Request;
use Exception;
use App\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleFacebookCallback()
    {
        try {

            $user = Socialite::driver('facebook')->stateless()->user();
            //dd($user);
            $finduser = User::where('google_id', $user->id)->first();
            $checkUser =  User::where(['email'=>$user->email])->first();

            if($finduser){

                Auth::login($finduser);

                return redirect()->route('my_home');

            }
            elseif($checkUser){
                
                Auth::login($checkUser);

                return redirect()->route('my_home');
            }
            else{

                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id'=> $user->id,
                    'password' => encrypt('123456dummy')
                ]);

                Auth::login($newUser);

                return redirect()->route('my_home');
            }

        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
