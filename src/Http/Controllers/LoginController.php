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
        $this->checkSocialsIsActive();

        return Socialite::driver($provider)->redirect();
    }


    public function handleProviderCallback($provider)
    {
        $this->checkSocialsIsActive();

        $socialUser = Socialite::driver($provider)->user();

        $user = User::where('email', $socialUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'email' => $socialUser->getEmail(),
                'password' => bcrypt(123456),
                config('socials.user_avatar') => $socialUser->getAvatar(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
            ]);
        }

        if ($user->provider != $provider) {
            abort(403, "Ошибка! Используйте другую учётную запись для авторизации!");
        }

        Auth::login($user);

        return redirect('/');
    }



    public function logout()
    {

        Auth::logout();
        return redirect('/');
    }


    private function checkSocialsIsActive()
    {
        if (!config('socials.isActive')) {
            abort(403, "Авторизация через социальные сети отключена!");
        }
    }
}
