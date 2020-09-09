<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    //
    protected $table = "registrations";

    public function invitation()
    {
        return $this->belongsTo('App\Invitation', 'invitation_id','id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id','id');
    }

}
