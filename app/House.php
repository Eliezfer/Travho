<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    //
    protected $fillable = [
        'address_id','user_id','description','price_for_day','status',
    ];

    public function address()
    {
        return $this->hasOne('App\Address','id','address_id');
        // se indica el mdelo, la llave en la tabla foranea y la llave local correspondiente
    }
}
