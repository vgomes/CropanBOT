<?php

namespace Cropan\Console;

use Cropan\Console\Commands\FetchTelegramUpdates;
use Cropan\Console\Commands\GetImages;
use Cropan\Console\Commands\SendImagesToGroup;

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
        SendImagesToGroup::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }
}
