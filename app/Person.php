<?php

namespace Cropan;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use Sluggable;
    protected $fillable = ['name', 'slug'];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    // Relationships
    public function pictures()
    {
        return $this->belongsToMany(Picture::class, 'people_pictures');
    }

    // Attributes
    public function getRatingAttribute()
    {
        return number_format($this->pictures->avg(function (Picture $picture) {
            return 10 * ($picture->yes / ($picture->yes + $picture->no));
        }), 2);
    }

    // Events
    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        static::creating(function (Person $person) {
            $person->name = ucwords(strtolower($person->name));

            return true;
        });
    }
}
