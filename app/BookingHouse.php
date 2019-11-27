<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingHouse extends Model
{
    //
    protected $fillable = [
		'user_id', 'house_id', 'check_in', 'check_out', 'status'
    ];

    protected $attributes = [
        'status' => 'in process'
    ];

 

  /**
  * Get the user that owns the Booking.
  */
  public function user()
  {
      return $this->belongsTo('App\User');
  }

  /**
  * Get the house that owns the Booking.
  */
  public function house()
  {
      return $this->belongsTo('App\House');
  }

  /*public queryStatus($status){
    return $query->where(status,)
  }*/

}
