<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    //
    protected $fillable = [
        'country','state','municipality','street','crossStreet1','crossStreet2',
        'house_number','suburb','postcode',
    ];
}
