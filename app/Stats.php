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
}
