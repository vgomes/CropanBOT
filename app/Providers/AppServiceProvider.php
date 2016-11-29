<?php

namespace Cropan\Providers;

use Cropan\Person;
use Cropan\Picture;
use Cropan\User;
use Cropan\Vote;
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
        if (\Schema::hasTable('people')) {
            $people = \Cache::get('people', Person::orderBy('name', 'ASC')->get());

            \View::share('people', $people);
        }

        $membersCount = User::all()->count();
        $pictures_count = Picture::sent()->count();
        $published_count = Picture::published()->count();

        $votes = Vote::all();

        $yes_votes = Vote::yes()->count();
        $no_votes = Vote::no()->count();

        $votes_count = $votes->count();

        $global_yes_percent = number_format(($yes_votes / $votes_count) * 100, 2);
        $global_no_percent = number_format(($no_votes / $votes_count) * 100, 2);

        $pictures_queue_count = Picture::queue()->count();

        \View::share('members_count', $membersCount);
        \View::share('pictures_count', $pictures_count);
        \View::share('published_count', $published_count);
        \View::share('votes_count', $votes_count);
        \View::share('global_yes_percent', $global_yes_percent);
        \View::share('global_no_percent', $global_no_percent);
        \View::share('pictures_queue_count', $pictures_queue_count);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
