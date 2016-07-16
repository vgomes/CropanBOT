<?php

namespace Cropan\Providers;

use Illuminate\Pagination\BootstrapFourPresenter;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::presenter(function($paginator) {
            return new BootstrapFourPresenter($paginator);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
