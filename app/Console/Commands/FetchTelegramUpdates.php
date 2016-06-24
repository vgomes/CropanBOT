<?php

namespace Cropan\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Telegram\Bot\Objects\Update;
use Cropan\Update as UpdateItem;

class FetchTelegramUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:getupdates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch updates from Telegram';

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
        $items = Collection::make(\Telegram::getUpdates(['offset' => -20]));

        $items->each(function (Update $update) {
            $item = new UpdateItem();
            $item->import($update);
        });
    }
}
