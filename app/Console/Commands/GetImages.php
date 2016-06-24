<?php

namespace Cropan\Console\Commands;

use Cropan\Picture;
use Cropan\Update;
use Illuminate\Console\Command;

class GetImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets the pictures sent to the bot and uploads them to Imgur if needed';

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
        $pictures = Update::where('type', 'private')->get();

        $pictures->each(function (Update $update) {
            if (filter_var($update->text, FILTER_VALIDATE_URL)) {
                if (stripos($update->text, 'i.imgur.com') === false) {
                    $url = uploadToImgur($update->text);
                } else {
                    $url = $update->text;
                }

                try {
                    Picture::create([
                        'update_id' => $update->update_id,
                        'url' => $url,
                        'user_id' => $update->user_id
                    ]);
                } catch (\PDOException $e) {
                    // Already stored
                    \Log::warning($e->getMessage());
                }
            }

            $update->delete();
        });
    }
}
