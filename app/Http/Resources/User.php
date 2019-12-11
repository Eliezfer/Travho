<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        //

        return[
            'id' => $this->id,
            'data' => [
            'name'    => $this->name,
            'user'   => $this->user,
            //'password'  => $this->password,
            'birthdate'      => $this->birthdate, // 1/05/1998
            'cellphone'  => $this->cellphone,
            'email'    => $this->email,
            //'api_token' => $this->api_token,
            ],
        'link' =>[
            'self' => config('app.url').":8000/api/v1/users/".$this->id
        ],
            
        ];


    }
}
