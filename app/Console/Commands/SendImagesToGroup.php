<?php

namespace Cropan\Console\Commands;

use Carbon\Carbon;
use Cropan\Picture;
use Cropan\User;
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
//        $lastSentUser = Picture::latest('sent_at')->first()->user_id;
//
//        $pictures_queued = Picture::queue();
//
//        if ($pictures_queued->count() > 0) {
//            $pictures = $pictures_queued->where('user_id', '<>', $lastSentUser)->get();
//
//            if ($pictures->count() > 0) {
//                $picture = $pictures->sortBy('created_at')->take(10)->random();
//            } else {
//                $picture = Picture::queue()->get()->sortBy('created_at')->take(10)->random();
//            }
//
//            $picture->sendToGroup();
//            $picture->sent_at = Carbon::now();
//            $picture->save();
//        }
        $ids_users_sent_images = Picture::sent()->orderBy('sent_at', 'desc')->pluck('user_id')->toArray();

        $total_tickets = [];

        User::all()->each(function (User $user) use ($ids_users_sent_images, &$total_tickets) {
            $pictures_queued = $user->pictures()->queue()->count();
            $images_since_his_last_sent = array_search($user->telegram_id, $ids_users_sent_images) + 1; // +1 because array starts at index 0

            $num_tickets = min(20, $images_since_his_last_sent) * $pictures_queued;
            $tickets = array_fill(0, $num_tickets, $user->telegram_id);

            $total_tickets = array_merge($total_tickets, $tickets);
            var_dump("$user->nickname | t: $num_tickets");
        });

        $winner = $total_tickets[mt_rand(0, count($total_tickets) - 1)];
        
    }
}


