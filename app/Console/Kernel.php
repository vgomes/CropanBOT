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
        \Cropan\Console\Commands\SaveDailyStats::class
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

        // Inocentada
        $schedule->call(function () {
            \Telegram::sendAudio([
                'chat_id' => env('TELEGRAM_GROUP_ID'),
                'audio' => storage_path('app/public/01.mp3')
            ]);
        })->cron("30 7 28 12 *");

        $schedule->call(function () {
            \Telegram::sendAudio([
                'chat_id' => env('TELEGRAM_GROUP_ID'),
                'audio' => storage_path('app/public/02.mp3')
            ]);
        })->cron("01 8 28 12 *");

        $schedule->call(function () {
            \Telegram::sendAudio([
                'chat_id' => env('TELEGRAM_GROUP_ID'),
                'audio' => storage_path('app/public/03.mp3')
            ]);
        })->cron("30 10 28 12 *");

        $schedule->call(function () {
            \Telegram::sendAudio([
                'chat_id' => env('TELEGRAM_GROUP_ID'),
                'audio' => storage_path('app/public/04.mp3')
            ]);
        })->cron("05 11 28 12 *");

        $schedule->call(function () {
            \Telegram::sendAudio([
                'chat_id' => env('TELEGRAM_GROUP_ID'),
                'audio' => storage_path('app/public/05.mp3')
            ]);
        })->cron("30 17 28 12 *");

        $schedule->call(function () {
            \Telegram::sendAudio([
                'chat_id' => env('TELEGRAM_GROUP_ID'),
                'audio' => storage_path('app/public/06.mp3')
            ]);
        })->cron("45 20 28 12 *");

        $schedule->call(function () {
            \Telegram::sendAudio([
                'chat_id' => env('TELEGRAM_GROUP_ID'),
                'audio' => storage_path('app/public/07.mp3')
            ]);
        })->cron("45 23 28 12 *");
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
