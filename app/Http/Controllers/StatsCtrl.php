<?php

namespace Cropan\Http\Controllers;

use Carbon\Carbon;
use Cropan\Repositories\StatsRepo;
use Cropan\Stats;
use Cropan\Vote;

class StatsCtrl extends Controller
{
    /**
     * @var StatsRepo
     */
    private $repo;

    public function __construct(StatsRepo $statsRepo)
    {
        $this->repo = $statsRepo;
    }

    public function global()
    {
        $globalImagesAreaGraph = $this->repo->getGlobalYearlyAreaGraph();

        $votes = Vote::all()->count();

        return view('pages.stats.global')
            ->with('globalImagesAreaGraph', $globalImagesAreaGraph);
//            ->with('globalImagesYesNoDonut', $globalImagesYesNoDonut)
//            ->with('globalStatsForYears', $statsForYears);
    }

    public function stats()
    {
        $stats = new Stats();

        $ratioTumblr = $stats->tumblrRanking();
        $ratioYLD = $stats->yesRatio();
        $ratioNO = $stats->noRatio();

        $uncommonTaste = $stats->uncommonTaste();
        $nitpicker = $stats->nitPicker();

        return view('pages.stats')
            ->with('ratioTumblr', $ratioTumblr)
            ->with('ratioYLD', $ratioYLD)
            ->with('ratioNO', $ratioNO)
            ->with('uncommonTaste', $uncommonTaste)
            ->with('nitpicker', $nitpicker);
    }
}
