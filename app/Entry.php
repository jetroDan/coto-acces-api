<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    //
    protected $table = "entries";

    public function exitguarddata()
    {
        return $this->belongsTo('App\User', 'exit_guard','id');
    }

    public function registration()
    {
        return $this->belongsTo('App\Registration', 'registration_id','id');
    }

    public function entryguarddata()
    {
        return $this->belongsTo('App\User', 'entry_guard','id');
    }
}
