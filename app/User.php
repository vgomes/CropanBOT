<?php

namespace Cropan;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $primaryKey = 'telegram_id';

    protected $fillable = [
        'nickname',
        'telegram_id',
    ];

    protected $hidden = ['remember_token'];

    protected $appends = ['level', 'current_exp'];

    // relationships
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function pictures()
    {
        return $this->hasMany(Picture::class);
    }

    public function getLevelAttribute()
    {
        return (int) ($this->exp / 1000) + 1;
    }

    public function getCurrentExpAttribute()
    {
        return (int) ($this->exp % 1000);
    }
}