<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingHouse extends Model
{
    //
    protected $fillable = [
		'user_id', 'house_id', 'check_in', 'check_out', 'date_request', 'status'
  ];

}
