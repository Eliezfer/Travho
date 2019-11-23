<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    //
    protected $fillable = [
        'address_id','user_id','description','priceForDay','Status',
    ];
}
