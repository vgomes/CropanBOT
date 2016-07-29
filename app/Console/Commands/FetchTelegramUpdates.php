<?php

namespace Cropan\Console\Commands;

use Carbon\Carbon;
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
        $items = Collection::make(\Telegram::getUpdates(['offset' => -100]));

        $items = $items->filter(function (Update $update) {
            return isAllowedUserId($update->getMessage()->getFrom()->getId());
        })
        ->filter(function (Update $update) {
            return $update->getMessage()->getChat()->getType() == 'private';
        })->each(function (Update $update) {
            $storedUpdate = \Cropan\Update::withTrashed()->where('update_id', $update->getUpdateId())->first();

            if (is_null($storedUpdate)) {
                $text = getPictureUrlFromTelegram($update);

                $data = [];
                $data['update_id'] = $update->getUpdateId();
                $data['user_id'] = $update->getMessage()->getFrom()->getId();
                $data['type'] = $update->getMessage()->getChat()->getType();
                $data['text'] = $text;
                $data['content'] = $update;
                $data['date'] = Carbon::createFromTimestamp($update->getMessage()->getDate());

                \Cropan\Update::create($data);
            }
        });
    }
}
