<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $table = "register_provider";

    protected $fillable = [
        'company_name',
        'phone_number',
        'direction',
        'user_name'
    ];
}
