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
        $a = PicStatsLog::get()->groupBy(function ($item) {
            return Carbon::parse($item->created_at)->year;
        });

        return array_keys($a->toArray());
    }

    public function getGlobarYearlyStatsGraph()
    {
        $data = [];

        foreach ($this->getDistinctYearsFromPictures() as $year) {

            $data[] = [
                'year' => $year,
                'data' => $this->yearlyBarGraph($year)
            ];
        }

        return json_encode($data);
    }

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

    public function getGlobalStatsForMonths()
    {
        $statsForYears = new Collection();
        $query = \DB::table('picture_stats_log')
            ->select(\DB::raw('YEAR(date) as year'))
            ->distinct()
            ->get();

        foreach ($query as $value) {
            $year = $value->year;

            $statsForYears->add($this->getGlobalStatsForMonth($month, $year = null));
        }

        return json_encode($statsForYears);
    }

    public function getGlobalStatsForMonth($month, $year = null)
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
}