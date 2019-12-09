<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\House as HouseResource;
use App\House;

class BookingHouseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $house=House::findorfail($this->house_id);
        $houseResource = new HouseResource($house);
        return [
            'type' => 'bookings house',
            'id' => $this->id,
            'user_id' => $this->user_id,
            'house_id'=>$this->house_id,
            
            'attributes' => [
                'check_in' => $this->check_in,
                'check_out'=> $this->check_out,
                'status' => $this->status,
            ],
            'house' => $houseResource,
            'link' =>  ['self' => route('bookings', $this->id)]

        ];
    }
}
