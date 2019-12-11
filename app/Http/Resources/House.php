<?php

namespace App\Http\Resources;
use App\Address;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class House extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //crear las reaciones para no usar findorfail

        return [
            'id'=>$this->id,
            'id_user'=>$this->user['id'],

            'data'=>[
                'description'=>$this->description,
                'price_for_day'=>$this->price_for_day,
                'status'=>$this->status,
                'country'=>$this->country,
                'state'=>$this->state,
                'municipality'=>$this->municipality,
            ],
            'address'=>[
                'street'=>$this->address['street'],
                'cross_street1'=>$this->address['cross_street1'],
                'cross_street2'=>$this->address['cross_street2'],
                'house_number'=>$this->address['house_number'],
                'suburb'=>$this->address['suburb'],
                'postcode'=>$this->address['postcode']
            ],
            'link'=>[
                'self'=>config('app.url').'/api/houses/'.$this->id,
            ],
            'User'=>[
                'data'=>[
                    'name'=>$this->user['name'],
                    'user'=>$this->user['user'],
                    'password'=>$this->user['password'],
                    'birthdate'=>$this->user['birthdate'],
                    'cellphone'=>$this->user['cellphone'],
                    'email'=>$this->user['email']
                ],
                'link'=>[
                    'self'=>config('app.url').'/api/user/'.$this->user['id'],
                ]
            ]

        ];
    }
}
