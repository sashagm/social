<?php

namespace Sashagm\Social\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Sashagm\Social\Traits\AuthTrait;
use Sashagm\Social\Traits\GuardTrait;

class AuthController extends Controller
{
    use AuthTrait, GuardTrait;

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        if (config('socials.isLoginForm') != true)
        {
            abort(403, 'Действие авторизации запрещено...');
        }

        $user = User::where('email', $request->input('email'))->first();

        if ($user) {

            $up = $this->authenticateUser($user, $request->input('password'));

            if($up){
                return redirect()
                        ->route(config('socials.redirect.auth'))
                        ->with('success', trans('social-auth::socials.login'));
            }
            else {
                return redirect()
                        ->back()
                        ->withErrors(['email' => trans('social-auth::socials.not_user')]);
                        // если пасс не верныцй
            }
            
        } else {
            return redirect()
                    ->back()
                    ->withErrors(['email' => trans('social-auth::socials.not_user')]);
        }
    }
}
