<?php

namespace Cropan\Http\Composers;

use Cropan\Picture;
use Cropan\User;
use Cropan\Vote;
use Illuminate\View\View;

class FooterComposer
{
    public function compose(View $view)
    {
        $membersCount = User::all()->count();
        $pictures_count = Picture::sent()->count();

        $votes = Vote::all();

        $yes_votes = Vote::yes()->count();
        $no_votes = Vote::no()->count();

        $votes_count = $votes->count();

        $global_yes_percent = number_format(($yes_votes / $votes_count) * 100, 2);
        $global_no_percent = number_format(($no_votes / $votes_count) * 100, 2);

        $pictures_queue_count = Picture::queue()->count();

        $view->with('members_count', $membersCount);
        $view->with('pictures_count', $pictures_count);
        $view->with('votes_count', $votes_count);
        $view->with('global_yes_percent', $global_yes_percent);
        $view->with('global_no_percent', $global_no_percent);
        $view->with('pictures_queue_count', $pictures_queue_count);
    }
}