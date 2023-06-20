<?php

namespace Sashagm\Social\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Sashagm\Social\Traits\GenPassTrait;
use Laravel\Socialite\Facades\Socialite;
use Sashagm\Social\Traits\FunctionTrait;
use Sashagm\Social\Traits\GuardTrait;

class LoginController extends Controller
{
    use GenPassTrait, GuardTrait, FunctionTrait;


    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }


    public function handleProviderCallback($provider)
    {

        $socialUser = Socialite::driver($provider)->user();

        $user = User::where(config('socials.user.email_colum'), $socialUser->getEmail())->first();

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

        if ($new) {

            return redirect()
                    ->route(config('socials.redirect.auth'))
                    ->with('success', trans('social-auth::socials.register'));
        } else {

            $this->updateUser($user, $socialUser);

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
