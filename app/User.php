<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = "users";

    public function invitations()
    {
        return $this->hasMany('App\Invitation');
    }

    public function coto()
    {
        return $this->belongsTo('App\Coto');
    }

    public function data()
    {
        return $this->hasOne('App\UserData');
    }
}
