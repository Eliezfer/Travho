<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    //
    protected $fillable = [
        'country','state','municipality','street','cross_street1','cross_street2',
        'house_number','suburb','postcode',
    ];
}
