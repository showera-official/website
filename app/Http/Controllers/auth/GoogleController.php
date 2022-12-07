<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use TheSeer\Tokenizer\Exception;


class GoogleController extends Controller
{
    function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();

    }
    function handleCallback()
    {


        try {
            $user = Socialite::driver('google')->user();

            $finduser = User::where('email', $user->email)->first();

            if ($finduser) {

                Auth::login($finduser);

                // return redirect('/home');

            } else {
                $newUser = User::updateOrCreate([
                    'name' => $user->name,
                    'email' => $user->email,
                    'social_id' => $user->id,
                    'social_type' => 'google',
                    // 'password' => encrypt('my-google')

                ]);

                Auth::login($newUser);

                // return redirect('/home');
            }

        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

}
