<?php

namespace Cropan;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Stats extends Model
{
    protected $users, $positiveRanking, $negativeRanking;

    public function globalImagesBarGraph()
    {
        $sent = Picture::sent()->count();
        $queue = Picture::queue()->count();
        $tumblr = Picture::published()->count();

        $collection = new Collection();

        $collection->add([
            'title' => 'Enviadas',
            'value' => $sent
        ]);

        $collection->add([
            'title' => 'En cola',
            'value' => $queue
        ]);
        $collection->add([
            'title' => 'Publicadas en Tumblr',
            'value' => $tumblr
        ]);

        return $collection->toJson();
    }

    /**
     * Data for a donut chart on number of approved/rejected images
     * @return string
     */
    public function globalImagesYesNoDonut()
    {
        $yes_images = Picture::yes()->count();
        $no_images = Picture::no()->count();

        $collection = new Collection();

        $collection->add([
            'label' => 'Sí',
            'value' => $yes_images
        ]);

        $collection->add([
            'label' => 'No',
            'value' => $no_images
        ]);

        return $collection->toJson();
    }

    public function getGlobalStatsForYears()
    {
        $statsForYears = new Collection();
        $query = \DB::table('picture_stats_log')
            ->select(\DB::raw('YEAR(date) as year'))
            ->distinct()
            ->get();

        foreach ($query as $value) {
            $year = $value->year;

            $statsForYears->add($this->getGlobalStatsForYear($year));
        }

        return json_encode($statsForYears);
    }

    public function getGlobalStatsForYear($year)
    {
        $result = \DB::table('picture_stats_log')
            ->whereYear('date', '=', $year)
            ->select([
                \DB::raw('year(date) as year'),
                \DB::raw('sum(sent) as sent'),
                \DB::raw('sum(published) as published'),
                \DB::raw('sum(images_positive) as images_positive'),
                \DB::raw('sum(images_negative) as images_negative'),
                \DB::raw('sum(votes) as votes'),
                \DB::raw('sum(votes_yes) as votes_yes'),
                \DB::raw('sum(votes_no) as votes_no'),
            ])
            ->get();

        $result = array_first($result);

        return $result;
    }



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
