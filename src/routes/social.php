<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Sashagm\Social\Http\Controllers\LoginController;



Route::group(['middleware' => ['web', 'guest'], 'prefix' => config('socials.admin_prefix') ], function () {
    Route::get('/login/{provider}', [LoginController::class, 'redirectToProvider'])->name('social-auth');
    Route::get('/login/{provider}/callback', [LoginController::class, 'handleProviderCallback'])->name('social-callback');
    
});

Route::group(['middleware' => ['web', 'auth']], function () {
   Route::post('/logout/social', [LoginController::class, 'logout'])->name('social-logout');
    
});






