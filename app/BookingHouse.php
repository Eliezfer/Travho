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

 
    public function scopeHouseBokings($query) 
    {
      return $query->where('user_id', auth()->user()->id )
            ->orderBy('id','DESC')
            ->paginate(1);
    }

    public function scopeBokingsOfYourHouse($query) 
    {
      return $query->join('booking_houses.house_id',  'houses.id')
                ->where('houses.user_id', auth()->user()->id)->get();
            ;
    }

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
