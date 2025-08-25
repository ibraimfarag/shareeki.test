<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use http\Message;
use Illuminate\Http\Request;
use Exception;
use App\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleGoogleCallback()
    {
        try {

            $user = Socialite::driver('google')->stateless()->user();
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
                    'email_verified_at' => Carbon::now(),
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
