<?php

namespace Cropan\Repositories;

use Carbon\Carbon;
use Cropan\PicStatsLog;
use Cropan\Picture;
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
}