<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
        $URL = env("APP_URL");
        return[
            'id' => $this->id,
            'data' => [
            'nombre'    => $this->name,
            'usuario'   => $this->user,
            //'password'  => $this->password,
            'date'      => $this->birthdate, // 1/05/1998
            'teléfono'  => $this->cellphone,
            'correo'    => $this->email,
            ],
        'link' =>[
            'self' => $URL.":8000/api/users/".$this->id
        ],
            
        ];


    }
}
