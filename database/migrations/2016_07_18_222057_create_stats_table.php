<?php

use Carbon\Carbon;
use Cropan\PicStatsLog;
use Cropan\Picture;
use Cropan\Vote;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('picture_stats_log', function (Blueprint $table) {
            $table->date('date');
            $table->unsignedInteger('sent')->default(0);
            $table->unsignedInteger('published')->default(0);
            $table->unsignedInteger('images_positive')->default(0);
            $table->unsignedInteger('images_negative')->default(0);
            $table->unsignedInteger('votes')->default(0);
            $table->unsignedInteger('votes_yes')->default(0);
            $table->unsignedInteger('votes_no')->default(0);

            $table->timestamps();

            $table->primary('date');
        });

        // populate with data from the database
        $firstDate = Picture::oldest()->first()->created_at;

        while ($firstDate->lt(Carbon::today())) {
            $date = $firstDate->toDateString();

            $sent = Picture::whereDate('created_at', '=', $date)->get()->count();
            $published = Picture::published()->whereDate('created_at', '=', $date)->get()->count();
            $images_positive = Picture::yes()->whereDate('created_at', '=', $date)->get()->count();
            $images_negative = Picture::no()->whereDate('created_at', '=', $date)->get()->count();

            $votes = Vote::whereDate('created_at', '=', $date)->get()->count();
            $votes_yes = Vote::yes()->whereDate('created_at', '=', $date)->get()->count();
            $votes_no = Vote::no()->whereDate('created_at', '=', $date)->get()->count();

            PicStatsLog::create([
                'date' => $date,
                'sent' => $sent,
                'published' => $published,
                'images_positive' => $images_positive,
                'images_negative' => $images_negative,
                'votes' => $votes,
                'votes_yes' => $votes_yes,
                'votes_no' => $votes_no
            ]);

            $firstDate->addDay();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('picture_stats_log');
    }
}
