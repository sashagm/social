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
                config('socials.user.pass_colum') => $this->generatePass(),
                config('socials.user.avatar') => $socialUser->getAvatar(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
            ]);
        }

        if ($user->provider != $provider) {
            abort(403, "Используйте другую учётную запись для авторизации!");
        }

        $this->isAccess($socialUser->getEmail());

        $user->updated_at = \Carbon\Carbon::now();
        $user->save();

        Auth::login($user);

        return redirect(config('socials.redirect.auth'));
    }



    public function logout()
    {
        Auth::logout();
        return redirect(config('socials.redirect.logout'));
    }


    private function checkSocialsIsActive()
    {
        if (!config('socials.isActive')) {
            abort(403, "Авторизация через социальные сети отключена Администрацией!");
        }
    }


    private function isAccess($email)
    {
        $user = User::where('email', $email)->first();
    
        if ($user && $user->{config('socials.user.access_colum')} == config('socials.user.access_value')) {
            abort(403, 'Ваш аккаунт заблокирован!');
        }
    }


    
    private function generatePass()
    {
        $method = config('socials.genPass.method');
        $filter = config('socials.genPass.filter');
        $secret = config('socials.genPass.secret');
    
        switch ($method) {
            case 'bcrypt':
                $pass = bcrypt($this->generateString($filter));
                break;
            case 'md5':
                $pass = md5($this->generateString($filter) . $secret);
                break;
            default:
                $pass = bcrypt($this->generateString($filter));
                break;
        }
    
        return $pass;
    }
    
    private function generateString($filter)
    {
        switch ($filter) {
            case 'str':
                $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'num':
                $characters = '0123456789';
                break;
            case 'hard':
                $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                break;
            default:
                $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                break;
        }
    
        $length = config('socials.genPass.length');
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $string;
    }


}
