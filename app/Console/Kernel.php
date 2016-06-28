<?php

namespace Cropan\Console;

use Cropan\Console\Commands\FetchTelegramUpdates;
use Cropan\Console\Commands\GetImages;
use Cropan\Console\Commands\ProcessVotes;
use Cropan\Console\Commands\SendImagesToGroup;

use Cropan\Console\Commands\SubmitApprovedToTumblr;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        FetchTelegramUpdates::class,
        GetImages::class,
        SendImagesToGroup::class,
        ProcessVotes::class,
        SubmitApprovedToTumblr::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('telegram:getupdates')->everyMinute();
        $schedule->command('images:get')->everyFiveMinutes();
        $schedule->command('images:votes')->everyTenMinutes();
        $schedule->command('images:submit')->everyTenMinutes();
        $schedule->command('images:tumblr')->everyThirtyMinutes();
    }
}
