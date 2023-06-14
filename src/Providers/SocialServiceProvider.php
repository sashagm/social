<?php

namespace Sashagm\Social\Providers;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory;


class SocialServiceProvider extends ServiceProvider
{


    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */


     public function boot()
     {

        $this->loadRoutesFrom(__DIR__.'/../routes/social.php');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'social-auth');


        $this->publishes([
            __DIR__.'/../config/socials.php' => config_path('socials.php'),
        ], 'social-auth');
        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/socials'),
        ], 'social-auth');





    }




     
}


