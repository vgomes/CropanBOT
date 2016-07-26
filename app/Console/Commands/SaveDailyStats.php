<?php

namespace Cropan\Console\Commands;

use Carbon\Carbon;
use Cropan\PicStatsLog;
use Cropan\Picture;
use Cropan\Vote;
use Illuminate\Console\Command;

class SaveDailyStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:save';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save info used for stats on a daily basis';

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
        $begin = Carbon::parse(PicStatsLog::orderBy('date', 'asc')->first()->date);
        $begin->startOfDay();

        $end = Carbon::yesterday()->endOfDay();

        while ($begin->lt($end)) {
            $date = $begin->toDateString();

            $sent = Picture::whereDate('created_at', '=', $date)->get()->count();
            $published = Picture::published()->whereDate('created_at', '=', $date)->get()->count();
            $images_positive = Picture::yes()->whereDate('created_at', '=', $date)->get()->count();
            $images_negative = Picture::no()->whereDate('created_at', '=', $date)->get()->count();

            $votes = Vote::whereDate('created_at', '=', $date)->get()->count();
            $votes_yes = Vote::yes()->whereDate('created_at', '=', $date)->get()->count();
            $votes_no = Vote::no()->whereDate('created_at', '=', $date)->get()->count();

            $item = PicStatsLog::where('date', $date)->get()->first();

            if (is_null($item)) {
                $item = PicStatsLog::create([
                    'date' => $date,
                    'sent' => $sent,
                    'published' => $published,
                    'images_positive' => $images_positive,
                    'images_negative' => $images_negative,
                    'votes' => $votes,
                    'votes_yes' => $votes_yes,
                    'votes_no' => $votes_no
                ]);
            } else {
                $item->sent = $sent;
                $item->published = $published;
                $item->images_positive = $images_positive;
                $item->images_negative = $images_negative;
                $item->votes = $votes;
                $item->votes_yes = $votes_yes;
                $item->votes_no = $votes_no;
                $item->save();
            }

            $begin->addDay();
        }
    }
}
