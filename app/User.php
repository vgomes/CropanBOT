<?php

namespace Cropan;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // relationships
    public function votes()
    {
        return $this->hasMany(Vote::class, 'user_id', 'telegram_id');
    }

    public function pictures()
    {
        return $this->hasMany(Picture::class, 'user_id', 'telegram_id');
    }
}
