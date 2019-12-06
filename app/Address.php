<?php

namespace App;
use App\House;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    //
    protected $fillable = [
        'country','state','municipality','street','cross_street1','cross_street2',
        'house_number','suburb','postcode',
    ];

    public function house()
{
    $house=House::find($this->id);
   return $this->belongsTo('App\House')->withDefault([$house]);
}


}
