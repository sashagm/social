<?php

namespace Sashagm\Social\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Sashagm\Social\Traits\GuardTrait;
use Sashagm\Social\Traits\GenPassTrait;
use Laravel\Socialite\Facades\Socialite;
use Sashagm\Social\Traits\BuildsLoggers;
use Sashagm\Social\Traits\FunctionTrait;

class LoginController extends Controller
{
    use GenPassTrait, GuardTrait, FunctionTrait, BuildsLoggers;


    public function redirectToProvider($provider)
    {
        $this->checkGateProvider($provider);

        return Socialite::driver($provider)
            ->redirect();
    }


    public function handleProviderCallback($provider)
    {
        $this->checkGateProvider($provider);

        $socialUser = Socialite::driver($provider)
                         ->user();

        $user = User::where(config('socials.user.email_colum'), $socialUser->getEmail())
                    ->first();

        $this->checkSocialsIsActive($user);

        $userData = $this->cast_fields($socialUser, $provider);

        $new = false;

        $this->feedback('before');

        if (!$user) {
            $user = User::create($userData);
            $new = true;
            $this->feedback('register');
        }

        $this->checkProvider($user, $provider);

        $this->isAccess($socialUser->getEmail());

        $this->feedback('after');

        Auth::login($user);

        $name =  config('socials.user.name_colum');

        if ($new) {
            
            if (config('socials.logger.log_login')) { 

                $this->logger('info', "The user: {$user->$name} has successfully registered in: {$_SERVER['REMOTE_ADDR']}"); 
            }

            return redirect()
                    ->route(config('socials.redirect.auth'))
                    ->with('success', trans('social-auth::socials.register'));
        } else {

            $this->updateUser($user, $socialUser);

            if (config('socials.logger.log_login')) { 

                $this->logger('info', "The user: {$user->$name} has successfully logged in: {$_SERVER['REMOTE_ADDR']}"); 
            }

            return redirect()
                    ->route(config('socials.redirect.auth'))
                    ->with('success', trans('social-auth::socials.login'));
        }
    }


    public function logout()
    {
        Auth::logout();

        return redirect()
                ->route(config('socials.redirect.logout'))
                ->with('success', trans('social-auth::socials.logout'));
    }


}
