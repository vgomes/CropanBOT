<?php

namespace Cropan\Console\Commands\Telegram;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Telegram\Bot\Objects\Update;

class FetchUpdates extends Command
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
        Collection::make(\Telegram::getUpdates(['offset' => -100]))
            ->filter(function (Update $update) {
                if (is_null($update->getMessage())) {
                    $message = $update->getEditedMessage();
                } else {
                    $message = $update->getMessage();
                }

                return isAllowedUserId($message->getFrom()->getId());
            })
            ->filter(function (Update $update) {
                if (is_null($update->getMessage())) {
                    $message = $update->getEditedMessage();
                } else {
                    $message = $update->getMessage();
                }

                return ($message->getChat()->getType() == 'private');
            })->each(function (Update $update) {
                $storedUpdate = \Cropan\Update::withTrashed()->where('update_id', $update->getUpdateId())->first();

                if (is_null($update->getMessage())) {
                    $message = $update->getEditedMessage();
                } else {
                    $message = $update->getMessage();
                }

                if (is_null($storedUpdate)) {
                    $text = getPictureUrlFromTelegram($update);

                    $data = [];
                    $data['update_id'] = $update->getUpdateId();
                    $data['user_id'] = $message->getFrom()->getId();
                    $data['type'] = $message->getChat()->getType();
                    $data['text'] = $text;
                    $data['content'] = $update;
                    $data['date'] = Carbon::createFromTimestamp($message->getDate());

                    \Cropan\Update::create($data);
                }
            });

        return true;
    }
}
