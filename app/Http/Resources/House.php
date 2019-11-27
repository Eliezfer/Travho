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
        $address=Address::findorfail($this->address_id);
        $user=User::findorfail($this->user_id);
        return [
            'id'=>$this->id,
            'id_user'=>$user['id'],
            'data'=>[
                'description'=>$this->description,
                'price_for_day'=>$this->price_for_day,
                'status'=>$this->status,
            ],
            'address'=>[
                'country'=>$address['coutry'],
                'state'=>$address['state'],
                'municipality'=>$address['municipality'],
                'street'=>$address['street'],
                'crossStreet1'=>$address['cross_street1'],
                'crossStreet2'=>$address['cross_street2'],
                'house_number'=>$address['house_number'],
                'suburb'=>$address['suburb'],
                'postcode'=>$address['postcode']
            ],
            'link'=>[
                'self'=>config('app.url').'/api/houses/'.$this->id,
            ],
            'User'=>[
                'data'=>[
                    'name'=>$user['name'],
                    'user'=>$user['user'],
                    'password'=>$user['password'],
                    'birthdate'=>$user['birthdate'],
                    'cellphone'=>$user['cellphone'],
                    'email'=>$user['email']
                ],
                'link'=>[
                    'self'=>config('app.url').'/api/user/'.$user['id'],
                ]
            ]
        ];
    }
}
