<?php

namespace Sashagm\Social\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Sashagm\Social\Traits\GenPassTrait;
use Laravel\Socialite\Facades\Socialite;
use Sashagm\Social\Traits\GuardTrait;

class LoginController extends Controller
{
    use GenPassTrait, GuardTrait;


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

        Auth::login($user);

        if ($new) {

            return redirect()
                ->route(config('socials.redirect.auth'))
                ->with('success', trans('social-auth::socials.register'));
        } else {

            $user->updated_at = \Carbon\Carbon::now();
            $user->save();

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


  



}
