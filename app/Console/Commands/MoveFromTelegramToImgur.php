<?php

namespace Cropan\Console\Commands;

use Cropan\Picture;
use Illuminate\Console\Command;

class MoveFromTelegramToImgur extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:telegramToImgur';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move pictures from api.telegram.org to imgur';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $images = Picture::where('url', 'LIKE', 'api.telegram.org')->get();

        $images->each(function (Picture $picture) {
            $picture->url = uploadToImgur($picture->url);
            $picture->save();
        });

        return true;
    }
}
