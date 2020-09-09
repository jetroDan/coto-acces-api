<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    //
    protected $table = "user_tokens";
    protected $fillable = ['token','user_id'];
}
