<?php

namespace Cropan\Http\Controllers;

use Cropan\Repositories\StatsRepo;
use Cropan\Stats;

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
        $totalImagesData = $this->repo->getPictureGlobalTotals();
        $getVotesGlobalTotals = $this->repo->getVotesGlobalTotals();
        $picturesPerHour = $this->repo->picturesPerHour();
        $votesPerHour = $this->repo->votesPerHour();

        return view('pages.stats.global')
            ->with('globalImagesAreaGraph', $globalImagesAreaGraph)
            ->with('totalImagesData', $totalImagesData)
            ->with('getVotesGlobalTotals', $getVotesGlobalTotals)
            ->with('picturesPerHour', $picturesPerHour)
            ->with('votesPerHour', $votesPerHour);
    }

    public function yearly($year)
    {
        $picturesData = $this->repo->getMonthlyAreaGraph($year);

        return view('pages.stats.yearly')->with('data', $picturesData);
    }

    public function statsUsers()
    {
        $usersBarGraph = $this->repo->usersPicturesBarGraph();

        return view('pages.stats.users')->with('usersBarGraph', $usersBarGraph);
    }
}
