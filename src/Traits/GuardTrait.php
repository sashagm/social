<?php

namespace  Sashagm\Social\Traits;


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
        $user = User::where('email', $email)->first();
    
        if (!$user) {
            abort(403,  trans('social-auth::socials.not_user'));
        }
    
        if ($user->{config('socials.user.access_colum')} == config('socials.user.access_value')) {
            abort(403,  trans('social-auth::socials.ban'));
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