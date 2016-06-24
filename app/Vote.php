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

        parent::created(function (Vote $vote) {
            if ($vote->vote) {
                $vote->picture->yes += 1;
            } else {
                $vote->picture->no += 1;
            }
            $vote->picture->save();
        });
    }
}
