<?php

namespace Cropan;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Stats extends Model
{
    protected $users, $positiveRanking, $negativeRanking;

    /**
     * Ranking for the ratio of pictures sent to Tumblr per user
     * @return mixed
     */
    public function tumblrRanking()
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
        $results = \DB::select("select u.nickname, count(u.id) as times
            from (select * from votes where vote = 1 ) v join ( select * from pictures where yes = 1 and score <= 0) p on v.picture_id = p.id
                join users u on v.user_id = u.telegram_id
            group by u.id
            order by times desc");

        $results = Collection::make($results);
        return $results;
    }

    /**
     * Ranking of users with the count of the times they have been the only one voting no when anyone else has voted yes
     * @return array|static|static[]
     */
    public function nitPicker()
    {
        $results = \DB::select("select u.nickname, count(u.id) as times
            from (select * from votes where vote = 0 ) v join ( select * from pictures where no = 1 and score >= 0) p on v.picture_id = p.id
                join users u on v.user_id = u.telegram_id
            group by u.id
            order by times desc");

        $results = Collection::make($results);
        return $results;
    }
}
