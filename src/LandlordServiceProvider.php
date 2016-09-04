<?php

namespace HipsterJazzbo\Landlord;

use HipsterJazzbo\Landlord\Facades\LandlordFacade;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

class LandlordServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // setup config
        $source = realpath(__DIR__ . '/../config/landlord.php');

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('landlord.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('landlord');
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Landlord::class, function () {
            return new Landlord();
        });

        // Define alias 'Landlord'
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->app->booting(function () {
                $loader = AliasLoader::getInstance();

                $loader->alias('Landlord', LandlordFacade::class);
            });
        }
    }
}
