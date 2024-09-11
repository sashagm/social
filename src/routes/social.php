<?php

use Illuminate\Support\Facades\Route;
use Sashagm\Social\Http\Controllers\AuthController;
use Sashagm\Social\Http\Controllers\LoginController;

$routes = config('socials.routes', []);

Route::group(['middleware' => ['web', 'guest'], 'prefix' => config('socials.admin_prefix')], function () use ($routes) {

    if (isset($routes['auth_login'])) {
        Route::get($routes['auth_login'][0], [LoginController::class, 'redirectToProvider'])
            ->name($routes['auth_login'][1]);
    }

    if (isset($routes['auth_login_callback'])) {
        Route::get($routes['auth_login_callback'][0], [LoginController::class, 'handleProviderCallback'])
            ->name($routes['auth_login_callback'][1]);
    }

    if (isset($routes['auth_login_form'])) {
        Route::get($routes['auth_login_form'][0], [AuthController::class, 'showLoginForm'])
            ->name($routes['auth_login_form'][1]);
    }

    if (isset($routes['auth_login_form_callback'])) {
        Route::post($routes['auth_login_form_callback'][0], [AuthController::class, 'login'])
            ->name($routes['auth_login_form_callback'][1]);
    }
});

Route::group(['middleware' => ['web', 'auth']], function () use ($routes) {

    if (isset($routes['social_logout'])) {
        Route::post($routes['social_logout'][0], [LoginController::class, 'logout'])
            ->name($routes['social_logout'][1]);
    }
});