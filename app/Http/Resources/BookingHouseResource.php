<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
        return [
            /*'type' => 'bookings house',
            'id' => $this->id,
            'user_id' => $this
            
            'attributes' => [

            ]*/
        ];
    }
}
