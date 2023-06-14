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

        $user = User::where(config('socials.user.email_colum'), $socialUser->getEmail())->first();

        $this->checkSocialsIsActive($user);

        $userData = [
            config('socials.user.name_colum') => $socialUser->getName() ?? $socialUser->getNickname(),
            config('socials.user.email_colum') => $socialUser->getEmail(),
            config('socials.user.pass_colum') => $this->generatePass(),
            config('socials.user.avatar') => $socialUser->getAvatar(),
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
        ];

        $customFields = config('socials.custom_fields');

        foreach ($customFields as $field => $value) {
            $userData[$field] = $value;
        }

        $new = false;

        if (!$user) {
            $user = User::create($userData);
            $new = true;
        }

        $this->checkProvider($user, $provider);

        $this->isAccess($socialUser->getEmail());

        $user->updated_at = \Carbon\Carbon::now();
        $user->save();

        Auth::login($user);

        if($new) {

            return redirect()
                    ->route(config('socials.redirect.auth'))
                    ->with('success', trans('social-auth::socials.register')); 
        
        } else {

            return redirect()
                    ->route(config('socials.redirect.auth'))
                    ->with('success', trans('social-auth::socials.login')); 

        }

       
    }



    public function logout()
    {
        Auth::logout();

        return redirect()->route(config('socials.redirect.logout'))->with('success', trans('social-auth::socials.logout')); 

    }


    private function checkSocialsIsActive($user = null)
    {
        $access = config('socials.access_admin');

        if ($user && in_array($user->id, $access)) {
            return true;
        } else {
            if (!config('socials.isActive')) {
                abort(403, trans('social-auth::socials.offline') );
            }
        }
    }


    private function isAccess($email)
    {
        $user = User::where('email', $email)->first();

        if ($user && $user->{config('socials.user.access_colum')} == config('socials.user.access_value')) {
            abort(403,  trans('social-auth::socials.ban') );
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

            case 'password_hash':
                $pass = password_hash($this->generateString($filter), PASSWORD_DEFAULT);
                break;

            case 'sha1':
                $pass = sha1($this->generateString($filter));
                break;

            case 'sha256':
                $pass = hash('sha256', $this->generateString($filter));
                break;

            case 'base64':
                $pass = base64_encode($this->generateString($filter));
                break;



            default:
                $pass = bcrypt($this->generateString($filter));
                break;
        }

        return $pass;
    }

    private function generateString($filter)
    {

        if (config('socials.genPass.default_gen')) {

            return config('socials.genPass.default_pass');
        } else {

            switch ($filter) {

                case 'string':
                    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    break;

                case 'number':
                    $characters = '0123456789';
                    break;

                case 'hard':
                    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                    break;

                case 'hard-unique':
                    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-+=[]{}|:;<>,.?/~';
                    break;

                case 'rus-string':
                    $characters = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ';
                    break;

                case 'rus-hard':
                    $characters = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ0123456789';
                    break;

                case 'rus-unique':
                    $characters = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ0123456789!@#$%^&*()_-+=[]{}|:;<>,.?/~';
                    break;

                default:
                    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                    break;
            }

            $minLength = config('socials.genPass.min');
            $maxLength = config('socials.genPass.max');
            $stableLength = config('socials.genPass.stable_length');


            if ($stableLength) {
                $length = config('socials.genPass.length');
            } else {
                $length = rand($minLength, $maxLength);
            }


            $string = '';
            for ($i = 0; $i < $length; $i++) {
                $string .= $characters[rand(0, strlen($characters) - 1)];
            }
            return $string;
        }
    }

    private function checkProvider($user, $provider)
    {
        $guard = config('socials.isProvider');

        switch ($guard) {
            case true:
                if ($user->provider == $provider) {
                    return true;
                } else {
                    abort(403, trans('social-auth::socials.provider'));
                }
                break;
            case false:
                return true;
                break;
        }
    }
}
