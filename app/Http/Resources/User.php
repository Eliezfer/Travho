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
            'nombre'    => $this->name,
            'usuario'   => $this->user,
            //'password'  => $this->password,
            'date'      => $this->birthdate, // 1/05/1998
            'teléfono'  => $this->cellphone,
            'correo'    => $this->email,
            //'api_token' => $this->api_token,
            ],
        'link' =>[
            'self' => config('app.url').":8000/api/users/".$this->id
        ],
            
        ];


    }
}
