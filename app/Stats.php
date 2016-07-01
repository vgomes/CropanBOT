<?php

namespace Cropan;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Stats extends Model
{
    protected $users, $positiveRanking, $negativeRanking;

    /**
     * Stats constructor.
     * Gets the users and does some math
     */
    public function __construct()
    {
        $this->users = User::has('votes')->get();

        $this->users->each(function (User $user) {
            $yes = 0;
            $no = 0;

            $user->votes()->each(function (Vote $vote) use (&$yes, &$no) {
                if ($vote->vote) {
                    $yes += 1;
                } else {
                    $no += 1;
                }
            });

            $user->yes = $yes;
            $user->no = $no;
            $user->yesPercent = $yes / ($yes + $no);
            $user->noPercent = $no / ($yes + $no);

            $user->sent = $user->pictures()->count();
            $user->published = Picture::where('user_id', $user->telegram_id)->published()->count();

            if ($user->sent > 0) {
                $user->publishedPercent = $user->published / $user->sent;
            } else {
                $user->publishedPercent = 0;
            }
        });
    }

    /**
     * Creates a ranking of pictures with the best score
     * @return mixed
     */
    public function positiveRanking()
    {
        $this->positiveRanking = Picture::has('votes')->sent()->orderBy('score', 'desc')->orderBy('yes', 'desc')->orderBy('no', 'asc')->take(12)->get();

        return $this->positiveRanking;
    }


    /**
     * Creates a ranking with the pictures with the best score
     * @return mixed
     */
    public function negativeRanking()
    {
        $this->negativeRanking = Picture::has('votes')->sent()->orderBy('score', 'asc')->orderBy('no', 'desc')->orderBy('yes', 'asc')->take(12)->get();

        return $this->negativeRanking;
    }

    /**
     * Ranking for the ratio of pictures sent to Tumblr per user
     * @return mixed
     */
    public function publishedInTumblrRanking()
    {
        return $this->users->sortByDesc('publishedPercent');
    }

    /**
     * Users ranked by yes ratio
     * @return mixed
     */
    public function yesRatio()
    {
        return $this->users->sortByDesc('yesPercent');
    }

    /**
     * Users by no ratio
     * @return mixed
     */
    public function noRatio()
    {
        return $this->users->sortByDesc('noPercent');
    }

    /**
     * Returns ranking of users with the count of times that every user has been the only one that has voted yes where
     * everyone else has voted no.
     * @return array|static|static[]
     */
    public function uncommonTaste()
    {
        $ids = [];

        $pics = \DB::table('pictures')
            ->where('score', '<=', 0)
            ->where('yes', 1)
            ->select('id')
            ->get();

        foreach ($pics as $pic) {
            $ids[] = intval($pic->id);
        }

        $results = \DB::table('votes')
            ->join('users', 'users.telegram_id', '=', 'votes.user_id')
            ->select('users.nickname', \DB::raw('count(users.id) as times'))
            ->whereIn('picture_id', $ids)
            ->groupBy('users.id')
            ->orderBy('times', 'desc')
            ->get();

        $results = Collection::make($results);
        return $results;
    }
    
    public function nitPicker()
    {
        $ids = [];

        $pics = \DB::table('pictures')
            ->where('score', '>=', 0)
            ->where('no', 1)
            ->select('id')
            ->get();

        foreach ($pics as $pic) {
            $ids[] = intval($pic->id);
        }

        $results = \DB::table('votes')
            ->join('users', 'users.telegram_id', '=', 'votes.user_id')
            ->select('users.nickname', \DB::raw('count(users.id) as times'))
            ->whereIn('picture_id', $ids)
            ->groupBy('users.id')
            ->orderBy('times', 'desc')
            ->get();

        $results = Collection::make($results);
        return $results;
    }
}
