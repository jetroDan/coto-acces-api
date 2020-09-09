<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = "register_services";

    protected $fillable = [
        'type_service',
        'name_service'
    ];
}
