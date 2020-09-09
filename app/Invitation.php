<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    //
    protected $table = "invitations";

    public function registrations()
    {
        return $this->hasMany('App\Registration', 'invitation_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id','id');
    }
}
