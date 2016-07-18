<?php

namespace Cropan\Providers;

use Illuminate\Support\ServiceProvider;

class FooterStatsProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('includes.footer', 'Cropan\Composers\FooterComposer');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
