<?php

namespace Sashagm\Social\Providers;

use Exception;
use Sashagm\Social\Traits\BladeTrait;
use Illuminate\Support\ServiceProvider;
use Sashagm\Social\Console\Commands\CreateCommand;
use Sashagm\Social\Console\Commands\AccessSocialsCommand;


class SocialServiceProvider extends ServiceProvider
{

    use BladeTrait;

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

        $this->registerRouter();

        $this->registerMigrate();

        $this->registerLang();

        $this->publishFiles();

        $this->registerCommands();

        $this->loadBlade();
    }

    protected function registerMigrate()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    protected function registerRouter()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/social.php');
    }

    protected function registerLang()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'social-auth');
    }

    protected function publishFiles()
    {
        $this->publishes([
            __DIR__ . '/../config/socials.php' => config_path('socials.php'),
        ], 'social-auth');
        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/socials'),
        ], 'social-auth');
    }

    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateCommand::class,
                AccessSocialsCommand::class,
            ]);
        }
    }

    protected function loadBlade()
    {
        $this->blade();

        $this->blade_btn();
    }
}
