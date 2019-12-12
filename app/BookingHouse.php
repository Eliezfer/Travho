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

 
    public function scopeMyBokings($query) 
    {
      return $query->where('user_id', auth()->user()->id )
            ->orderBy('id','DESC')
            ->paginate(5);
    }

    public function scopeBookingsFromMyHouse($query) 
    {
      return $query->join('houses','booking_houses.house_id','=','houses.id')
            ->where('houses.user_id',auth()->user()->id)
            ->select('booking_houses.*')
            ->paginate(5);
    }

    public function scopeBookingsAccept($query, $houseID){
      return $query ->where('house_id', $houseID)
            ->where('status', 'accepted');
                    
    }

    public function scopeBookingsBetweenDate($query, $checkIn, $checkOut){
      return $query ->whereBetween('check_in', [$checkIn,$checkOut])
            ->orWhereBetween('check_out', [$checkIn,$checkOut]);
    }

    public function scopeUpdateBookingsToCancel($query, $houseID){
      return $query  ->where('house_id', $houseID)
            ->update(['status'=>'canceled']);
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
