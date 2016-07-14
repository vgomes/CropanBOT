<?php

namespace Cropan;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $table = 'votes';
    protected $fillable = ['picture_id', 'user_id', 'vote'];

    // Relationships
    public function picture()
    {
        return $this->belongsTo(Picture::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Events
    static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        parent::saved(function (Vote $vote) {
            $vote->picture->yes = Vote::where('picture_id', $vote->picture_id)
                ->where('vote', true)
                ->get()->count();

            $vote->picture->no = Vote::where('picture_id', $vote->picture_id)
                ->where('vote', false)
                ->get()->count();
            
            $vote->picture->save();
        });

        parent::updated(function (Vote $vote) {
            Diary::experienceFromVoteForImageSubmitter($vote, true);
        });

        parent::created(function (Vote $vote) {
            Diary::experienceFromVote($vote);
            Diary::experienceFromVoteForImageSubmitter($vote);
        });
    }
}
