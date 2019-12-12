<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\House as HouseResource;
use App\Http\Resources\User as UserResource;
use App\House;
use App\User;

class BookingHouse extends JsonResource
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
        $user = User::findorfail($this->user_id);
        $userResource = new UserResource($user);
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
            'traveler' => $userResource,
            'link' =>  ['self' => route('bookings', $this->id)]

        ];
    }
}
