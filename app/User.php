<?php

namespace Cropan;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nickname', 'telegram_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['remember_token'];

    // relationships
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function pictures()
    {
        return $this->hasMany(Picture::class);
    }
}