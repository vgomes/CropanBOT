<?php

namespace Cropan\Console;

use Cropan\Console\Commands\FetchTelegramUpdates;
use Cropan\Console\Commands\GetImages;
use Cropan\Console\Commands\ProcessVotes;
use Cropan\Console\Commands\SaveDailyStats;
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
        SendImagesToGroup::class,
        ProcessVotes::class,
        SubmitApprovedToTumblr::class,
        SaveDailyStats::class
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
//        $schedule->command('images:get')->everyFiveMinutes();
        $schedule->command('images:votes')->everyTenMinutes();
        $schedule->command('images:submit')->cron("0 0,1,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23 * * *");
        $schedule->command('images:tumblr')->everyThirtyMinutes();

        $schedule->command('stats:save')->dailyAt('0:15');

        // Backups
        $schedule->command('backup:run')->hourly();
        $schedule->command('backup:monitor')->hourly();
        $schedule->command('backup:clean')->dailyAt('02:00');
    }
}
