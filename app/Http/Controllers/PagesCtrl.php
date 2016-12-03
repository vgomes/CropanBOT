<?php

namespace Cropan\Http\Controllers;

use Cropan\Http\Requests\TagRequest;
use Cropan\Http\Requests\UntagRequest;
use Cropan\Http\Requests\VoteRequest;
use Cropan\Person;
use Cropan\Picture;
use Cropan\Vote;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class PagesCtrl extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    /**
     * Front page for non-logged in users.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('pages.index');
    }

    /**
     * Front page for logged users
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home()
    {
        $pictures = Picture::published()->orderBy('published_at', 'desc')->orderBy('id', 'desc')->paginate(16);

        return view('pages.home')->with('pictures', $pictures);
    }

    /**
     * PAge showing all images sent to the telegram group
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function history()
    {
        $pictures = Picture::sent()->orderBy('published_at', 'desc')->orderBy('id', 'desc')->paginate(16);

        return view('pages.home')->with('pictures', $pictures);
    }

    /**
     * Page showing images sorted by their score
     * @param string $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ranking($order = 'desc')
    {
        if ($order == 'desc') {
            $pictures = Picture::sent()->orderBy('score', 'desc')->orderBy('yes', 'desc')->orderBy('updated_at', 'desc')->paginate(16);
        } else {
            $pictures = Picture::sent()->orderBy('score', 'asc')->orderBy('no', 'desc')->orderBy('updated_at', 'desc')->paginate(16);
        }

        return view('pages.home')->with('pictures', $pictures);
    }

    /**
     * Page that shows to the user all images he has sent
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sent()
    {
        $pictures = Picture::whereUserId(\Auth::user()->telegram_id)->orderBy('created_at', 'desc')->paginate(16);

        return view('pages.home')->with('pictures', $pictures);
    }

    /**
     * Page showing a list of people tagged in the pictures
     * @param string $criteria
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function directory($criteria = 'alphabet')
    {
        switch ($criteria) {
            case 'alphabet' :
                $people = Person::with('pictures')
                    ->has('pictures')
                    ->orderBy('name', 'ASC')
                    ->get()
                    ->groupBy(function (Person $person) {
                        return $person->name[0];
                    });

                return view("pages.directory.alphabet")->with('people', $people);

            case 'rating' :
                $people = Person::with('pictures')
                    ->has('pictures')
                    ->get()
                    ->sortByDesc(function (Person $person) {
                        return $person->rating;
                    })->filter(function (Person $person) {
                        return ($person->pictures->count() > 4);
                    });

                return view("pages.directory.rating")->with('people', $people);
        }
    }

    /**
     * Page showing all the images for a given person
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function person($slug)
    {
        $person = Person::whereSlug($slug)->first();
        $pictures = $person->pictures()->orderBy('score', 'DESC')->orderBy('yes', 'DESC')->paginate(16);

        return view('pages.directory.person')->with('person', $person)->with('pictures', $pictures);
    }

    /**
     * Page showing an individual image
     * @param Picture $picture
     * @param null $choice
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function picture(Picture $picture, $choice = null)
    {
        $picture = $picture->fresh(['people']);

        $vote = Vote::wherePictureId($picture->id)
            ->whereUserId(\Auth::user()->telegram_id)
            ->first();

        if (!is_null($choice)) {
            $choice = (strtoupper($choice) == 'YLD');

            if (is_null($vote)) {
                $vote = new Vote();
                $vote->picture_id = $picture->id;
                $vote->user_id = \Auth::user()->telegram_id;
            }

            $vote->vote = $choice;
            $vote->save();
        }

        return view('pages.picture')->with('picture', $picture)->with('vote', $vote);
    }

    /**
     * Page showing all the images that the logged in user didn't vote
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pending()
    {
        $pic = \DB::select("select * 
            from pictures p 
            where p.id not in (
                select distinct v.picture_id 
                from votes v 
                where v.user_id = ?
            ) and sent_at is not null
            order by rand()
            limit 1", [\Auth::user()->telegram_id]);

        $picture = null;

        if (count($pic) > 0) {
            $picture = Picture::find($pic[0]->id);
        }

        return view('pages.pending')->with('picture', $picture)->with('vote', null);
    }

    public function unnamed()
    {
        $picture = Picture::sent()->doesntHave('people')->get()->random();
        $vote = Vote::wherePictureId($picture->id)
            ->whereUserId(\Auth::user()->telegram_id)
            ->first();

        return view('pages.picture')->with('picture', $picture)->with('vote', $vote);
    }

    /**
     * Page that registers votes for the pending page
     * @param VoteRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function vote(VoteRequest $request)
    {
        $vote = Vote::firstOrNew([
            'picture_id' => $request->get('picture_id'),
            'user_id'    => \Auth::user()->telegram_id
        ]);

        $vote->vote = $request->get('vote');
        $vote->save();

        return \Redirect::back();
    }

    /**
     * Page that registers tag requests
     * @param TagRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function tag(TagRequest $request)
    {
        $picture = Picture::findOrFail($request->get('picture_id'));

        foreach ($request->get('people') as $person) {
            if (is_numeric($person)) { // value is 'id' of person on database
                try {
                    $picture->people()->attach($person);
                } catch (QueryException $exception) {}
            } else { // value is name for a new person
                $newPerson = Person::firstOrCreate(['name' => $person]);

                try {
                    $picture->people()->attach($newPerson->id);
                } catch (QueryException $exception) {}
            }
        }

        return \Redirect::back();
    }

    /**
     * Page that registers untag requests
     * @param UntagRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function untag(UntagRequest $request)
    {
        $picture = Picture::findOrFail($request->get('picture_id'));

        try {
            $picture->people()->detach($request->get('person_id'));
        } catch (QueryException $exception) {}

        return \Redirect::back();
    }
}
