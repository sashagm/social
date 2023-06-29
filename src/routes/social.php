<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Sashagm\Social\Http\Controllers\LoginController;



$routes = config('socials.routes');

Route::group(['middleware' => ['web', 'guest'], 'prefix' => config('socials.admin_prefix')], function () use ($routes) {
    
    Route::get($routes['auth_login'][0], [LoginController::class, 'redirectToProvider'])
            ->name($routes['auth_login'][1]);
            
    Route::get($routes['auth_login_callback'][0], [LoginController::class, 'handleProviderCallback'])
            ->name($routes['auth_login_callback'][1]);
});

Route::group(['middleware' => ['web', 'auth']], function () use ($routes) {
    
    Route::post($routes['social_logout'][0], [LoginController::class, 'logout'])
            ->name($routes['social_logout'][1]);

});
