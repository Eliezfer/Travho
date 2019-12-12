<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $URL = env("APP_URL");
        // return parent::toArray($request);
        return[
            '*' =>[
                    'id' => $this->id,
                    'data' => [
                    'nombre'    => $this->name,
                    'usuario'   => $this->username,
                    'password'  => $this->password,
                    'date'      => $this->date, // 1/05/1998
                    'teléfono'  => $this->cellphone,
                    'correo'    => $this->email,
                    //'api_token' => $this->api_token
                    ],
                'link' =>[
                    'self' => "https://".$URL."/api/v1/products/".$this->id
                ],
            ]
        ];
    }
}
