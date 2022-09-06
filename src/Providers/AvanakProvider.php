<?php

namespace hopeofiran\avanak\Providers;

use hopeofiran\avanak\Avanak;
use Illuminate\Support\ServiceProvider;

class AvanakProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->publishes([
            Avanak::getDefaultConfigPath() => config_path('avanak.php'),
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('avanak', function () {
            $config = config('avanak') ?? [];
            return new Avanak($config);
        });
    }
}
