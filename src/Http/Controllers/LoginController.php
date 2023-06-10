<?php

namespace Sashagm\Social\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;


class LoginController extends Controller
{
    public function redirectToProvider($provider)
    {
   
        return Socialite::driver($provider)->redirect();

    }


    public function handleProviderCallback($provider)
    {
        $socialUser = Socialite::driver($provider)->user();

        $user = User::where('email', $socialUser->getEmail())->first();

        if ($provider != $user->provider) {
            abort(403, "Ошибка! Используйте другую учётную запись для авторизации!");
        }
    
        if (! $user) {
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'password' => bcrypt(123456),
                config('socials.user_avatar') => $socialUser->getAvatar(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
            ]);
        }
    
        Auth::login($user);
    
        return redirect('/');
    }


    public function logout()
    {

        Auth::logout();
        return redirect('/');

    }



}
