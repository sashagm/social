<?php

namespace  Sashagm\Social\Traits;


use Exception;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait GuardTrait
{


    private function checkSocialsIsActive($user = null)
    {
        $access = config('socials.access_admin');
    
        if ($user && in_array($user->id, $access)) {
            return true;
        }
    
        if (!config('socials.isActive')) {
            abort(403, trans('social-auth::socials.offline'));
        }
    }
       
    private function isAccess($email)
    {
        if (!config('socials.user.access_colum') || !config('socials.user.access_value')) {
            throw new \InvalidArgumentException('Social auth configuration error: access_colum or access_value not set');
        }

        $user = User::where('email', $email)
                        ->first();
    
        if (!$user) {
            abort(403,  trans('social-auth::socials.not_user'));
        }
    
        if ($user->{config('socials.user.access_colum')} == config('socials.user.access_value')) {
            abort(403,  trans('social-auth::socials.ban'));
        }
    }
    
    private function checkProvider($user, $provider)
    {
        if (!$user) {
            throw new \InvalidArgumentException('User not found!');
        }
    
        if (!$provider) {
            throw new \InvalidArgumentException('Provider not specified!');
        }
    
        if (!config('socials.isProvider')) {
            throw new \InvalidArgumentException('Provider not found!');
        }
        
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
    
    private function checkGateProvider($provider)
    {
        $allowedProviders = config('socials.providers');

        if (!$allowedProviders) {
            throw new \InvalidArgumentException('Provider not found to array providers!');
        }

        if (!$provider) {
            throw new \InvalidArgumentException('Provider not found!');
        }

        if (!in_array($provider, $allowedProviders)) {
            throw new Exception('Invalid social provider.');
        }
    }


}