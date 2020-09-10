<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    protected $table = "my_business";

    protected $fillable = [
        'name',
        'direction',
        'schedule_one',
        'schedule_two',
        'rfc',
        'phone_number_one',
        'phone_number_two',
        'web_page',
        'category_of_services',
        'type_of_service',
        'price_range_one',
        'price_range_two',
        'way_to_pay',
        'clabe',
        'card_number',
        'distance_limit'
    ];
}
