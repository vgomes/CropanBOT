<?php

namespace Cropan\Console;

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
        \Cropan\Console\Commands\Telegram\FetchUpdates::class,
        \Cropan\Console\Commands\Telegram\SendImageToGroup::class,
        \Cropan\Console\Commands\Telegram\MoveFromTelegramToImgur::class,
        \Cropan\Console\Commands\SaveDailyStats::class,
        \Cropan\Console\Commands\Maintenance\Remove404Pictures::class
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
        $schedule->command('telegram:sendImageGroup')->cron("0 0,1,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23 * * *");

        $schedule->command('stats:save')->dailyAt('0:15');

        // Backups
        $schedule->command('backup:run')->hourly();
        $schedule->command('backup:monitor')->dailyAt('01:00');
        $schedule->command('backup:clean')->dailyAt('02:00');

        $schedule->command('images:telegramToImgur')->hourly();

        $schedule->command('maintenance:404')->dailyAt('03:30');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
