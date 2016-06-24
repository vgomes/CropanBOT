<?php

namespace Cropan\Console\Commands;

use Cropan\Picture;
use Cropan\Update;
use Cropan\Vote;
use Illuminate\Console\Command;

class ProcessVotes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:votes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process votes for images';

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
        $updates = Update::all();

        $updates->each(function (Update $update) {
            if (!is_null($update->reply_to)) {
                $picture = Picture::where('url', $update->reply_to)->first();

                if (!is_null($picture)) {
//                    var_dump($picture->url);
                    $text = strtoupper(trim($update->text));

                    switch ($text) {
                        case 'YLD' :
                            $vote = true;
                            break;

                        case 'NO':
                            $vote = false;
                            break;
                    }

                    try {
                        Vote::create([
                            'picture_id' => $picture->id,
                            'user_id' => $update->user_id,
                            'vote' => $vote
                        ]);
                    } catch (\PDOException $e) {
                        // Already stored
                    }
                }
            }

//            $update->delete();
        });
    }
}
