<?php

namespace Cropan\Repositories;

use Carbon\Carbon;
use Cropan\PicStatsLog;
use Cropan\Picture;
use Cropan\User;
use Cropan\Vote;
use Illuminate\Database\Eloquent\Collection;

class StatsRepo
{
    /**
     * Gets an array of the years with pictures stored on the database
     * @return array
     */
    public function getDistinctYearsFromPictures()
    {
        $years = [];

        $query = \DB::table('picture_stats_log')
            ->select(\DB::raw('YEAR(date) as year'))
            ->distinct()
            ->get();

        foreach ($query as $value) {
            $years[] = $value->year;
        }

        return $years;
    }

    /**
     * Gets data for all year's global graphs
     * @return string
     */
    public function getGlobalYearlyAreaGraph()
    {
        $data = [];

        foreach ($this->getDistinctYearsFromPictures() as $year) {

            $data[] = [
                'year' => $year,
                'areaGraph' => $this->yearlyBarGraph($year),
                'donutGraph' => $this->yearlyDonutGraph($year)
            ];
        }

        return json_encode($data);
    }

    /**
     * Gathers data for graphs
     * @param Carbon $start
     * @param Carbon $end
     * @return Collection|static[]
     */
    public function globalBarGraph(Carbon $start, Carbon $end)
    {
        $data = PicStatsLog::whereBetween('date', [$start->toDateString(), $end->toDateString()])->get();
        return $data;
    }

    public function yearlyBarGraph($year)
    {
        $begin = Carbon::create($year)->startOfYear();
        $end = Carbon::create($year)->endOfYear();

        $data = $this->globalBarGraph($begin, $end)->groupBy(function (PicStatsLog $item) {
            return Carbon::parse($item->date)->month;
        });

        $results = new Collection();

        foreach ($data as $key => $value) {
            $result = new PicStatsLog();
            $result->month = Carbon::create(null, $key)->formatLocalized("%B");
            $result->sent = $value->sum('sent');
            $result->published = $value->sum('published');
            $result->images_positive = $value->sum('images_positive');
            $result->images_negative = $value->sum('images_negative');

            $results->add($result);
        }

        return $results->toArray();
    }

    public function globalVotesDonutGraph(Carbon $start, Carbon $end)
    {
        $data = \DB::table('picture_stats_log')
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->select(\DB::raw('sum(votes_yes) as yes'), \DB::raw('sum(votes_no) as no'))
            ->get();

        $data = array_first($data);
        $total = $data->yes + $data->no;

        $result = [];
        $result[] = ['label' => 'Sí', 'value' => $data->yes, 'total' => $total];
        $result[] = ['label' => 'No', 'value' => $data->no, 'total' => $total];

        return $result;
    }

    public function yearlyDonutGraph($year)
    {
        $begin = Carbon::create($year)->startOfYear();
        $end = Carbon::create($year)->endOfYear();

        $result = $this->globalVotesDonutGraph($begin, $end);

        return $result;
    }

    public function getPictureGlobalTotals()
    {
        $pictures_count = Picture::sent()->count();
        $published_count = Picture::published()->count();
        $pictures_queue_count = Picture::queue()->count();
        $approved = Picture::yes()->count();
        $rejected = Picture::no()->count();

        $data = [
            'Enviadas' => $pictures_count,
            'Publicadas' => $published_count,
            'En cola' => $pictures_queue_count,
            'Aprobadas' => $approved,
            'Rechazadas' => $rejected
        ];

        $result = [];
        $keys = array_keys($data);
        $values = array_values($data);

        for ($key = 0; $key < count($keys); $key++) {
            $result[] = [
                'title' => $keys[$key],
                'value' => $values[$key]
            ];
        }

        return json_encode($result);
    }

    public function getVotesGlobalTotals()
    {
        $yes_votes = Vote::yes()->count();
        $no_votes = Vote::no()->count();

        $total = $yes_votes + $no_votes;

        $result = [];
        $result[] = ['label' => 'Sí', 'value' => $yes_votes, 'total' => $total];
        $result[] = ['label' => 'No', 'value' => $no_votes, 'total' => $total];

        return json_encode($result);
    }

    public function getMonthlyAreaGraph($year)
    {
        $begin = Carbon::create($year)->startOfYear();
        $end = Carbon::create($year)->endOfYear();

        $data = $this->globalBarGraph($begin, $end)->groupBy(function (PicStatsLog $item) {
            return Carbon::parse($item->date)->formatLocalized("%B");
        });

        return $data->toJson();
    }

    public function votesPerHour()
    {
        $data = \DB::table('votes')
            ->groupBy('vote')
            ->groupBy(\DB::raw('HOUR(created_at)'))
            ->select([\DB::raw('HOUR(created_at) as hour'), \DB::raw('COUNT(id) as value'), 'vote'])
            ->get();

        $results = array_fill(0, 23, ['yes' => 0, 'no' => 0, 'total' => 0]);

        $votes = Vote::count();

        foreach ($data as $item) {
            switch ($item->vote) {
                case 0 :
                    $results[$item->hour]['no'] = number_format(($item->value / $votes) * 100, 2);
                    break;

                case 1 :
                    $results[$item->hour]['yes'] = number_format(($item->value / $votes) * 100, 2);
                    break;
            }
        }

        foreach ($results as $key => $hour) {
            $results[$key]['hour'] = $key;
        }

        return $results;
    }

    public function picturesPerHour()
    {
        $data = \DB::table('pictures')
            ->groupBy(\DB::raw('HOUR(created_at)'))
            ->select([\DB::raw('HOUR(created_at) as hour'), \DB::raw('COUNT(id) as value')])
            ->get();

        $results = array_fill(0, 23, null);

        $pictures = Picture::count();

        foreach ($results as $key => $hour) {
            $results[$key]['hour'] = $key;
            $results[$key]['value'] = 0;
        }

        foreach ($data as $item) {
            $results[$item->hour]['value'] = number_format(($item->value / $pictures) * 100, 2);
        }

        return $results;
    }

    public function usersPicturesBarGraph()
    {
        $users = User::with(['pictures'])->get();

        $data = [];

        $users = $users->each(function (User $user) use ($data) {
            $user->sent = $user->pictures->count();
            $user->published = $user->pictures()->published()->count();
            $user->publishedRatio = (float)number_format($user->published / $user->sent, 6);
        })->sortByDesc('publishedRatio');

        $users->each(function (User $user) use (&$data) {
            $data[] = [
                'nickname' => $user->nickname,
                'sent' => $user->pictures->count(),
                'published' => $user->pictures()->published()->count(),
                'publishedRatio' => (float)number_format($user->published / $user->sent, 6)
            ];
        });

        return $data;
    }

    public function usersVotesBarGraph()
    {
        $users = User::with(['votes'])->get();
        $pictures = Picture::sent()->count();

        $data = [];

        $users = $users->each(function (User $user) use ($data, $pictures) {
            $user->votesCasted = $user->votes->count();
            $user->votesYes = (float) number_format(100 * $user->votes()->yes()->count() / $user->votesCasted, 4);
            $user->votesNo = (float) number_format(100 * $user->votes()->no()->count() / $user->votesCasted, 4);
            $user->votedRatio = (float) number_format(100 * $user->votesCasted / $pictures, 6);
        })->sortByDesc('votedRatio');

        $users->each(function (User $user) use (&$data) {
            $data[] = [
                'nickname' => $user->nickname,
                'yes' => $user->votesYes,
                'no' => $user->votesNo,
                'ratio' => $user->votedRatio
            ];
        });

        return $data;
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