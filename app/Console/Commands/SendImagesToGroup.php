<?php

namespace Cropan\Console\Commands;

use Cropan\Picture;
use Illuminate\Console\Command;

class SendImagesToGroup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:submit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Submits one image sent to the bot to the group configured on the .env file';

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
        $lastSentUser = Picture::latest('sent_at')->first()->user_id;

        $picture = Picture::where('sent_at', null)->where('user_id', '<>', $lastSentUser)->orderBy('created_at', 'asc')->get()->take(10);

        if ($picture->count() > 0) {
            $picture = $picture->random(1);
            $picture->sendToGroup();
        }
    }
}
