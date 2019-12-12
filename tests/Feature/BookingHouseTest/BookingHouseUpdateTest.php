<?php

namespace Tests\Feature\BookingTest;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\BookingHouse;
use App\House;
use App\Address;
use App\User;

class BookingHouseUpdateTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_host_can_update_a_booking_to_accept()
    {
        $booking = factory(BookingHouse::class)->create([
            "check_in"=>"2019-12-25 16:00:00",
            "check_out"=>"2019-12-26 16:00:00"]);
        $house = House::findorfail($booking['house_id']);
        $address = Address::find($house->address_id);
        $traveler = User::findorfail($booking['user_id']);
        $host = User::findorfail($house['user_id']);
        $bookingData = [
            "data" => [
                "attributes"=>[
                    "status"=>"accepted"
                ]
            ]
        ];
        $response = $this->actingAs($host)
        ->json('PUT', '/api/v1/bookings/'.$booking['id'].'?api_token='.$host['api_token'],$bookingData);
        $response->assertStatus(200); 
        $response->assertJsonStructure([
            'data'=> [
                'type',
                'id',
                'user_id',
                'house_id',
                'attributes' => [
                    'check_in',
                    'check_out',
                    'status'
                ],
                'house' => [
                    'id',
                    'id_user',
                    'data'=>[
                        'description',
                        'price_for_day',
                        'status',
                        'state',
                        'municipality',
                    ],
                    'address'=>[
                        'street',
                        'cross_street1',
                        'cross_street2',
                        'house_number',
                        'suburb',
                        'postcode'
                    ],
                    'link'=>[
                        'self'
                    ],
                    'User'=>[
                        'data'=>[
                            'name',
                            'user',
                            'birthdate',
                            'cellphone',
                            'email',
                        ],
                        'link'=>[
                            'self'
                        ]
                    ]
                ],
                'traveler'=>[
                    'id',
                    'data'=>[
                        'name',
                        'user',
                        'birthdate',
                        'cellphone',
                        'email',
                    ],
                    'link'=>[
                        'self'
                    ]
                ],
                'link'=>[
                    'self'
                ]
            ]
        ]);

        $body = $response->decodeResponseJson();

        $response->assertJsonFragment([
            'data'=> [
                'type' => 'bookings house',
                'id' => $body['data']['id'],
                'user_id' => $traveler->id,
                'house_id' => $house->id,
                'attributes' => [
                    'check_in' => $body['data']['attributes']['check_in'],
                    'check_out' => $body['data']['attributes']['check_out'],
                    'status' => 'accepted'
                ],
                'house' => [
                    'id' => $house->id,
                    'id_user'=> $house->user_id,
                    'data'=>[
                        'description'=> $house->description,
                        'price_for_day'=> "$house->price_for_day",
                        'status' => $house->status,
                        'state' => $house->state,
                        'municipality' => $house->municipality,
                    ],
                    'address'=>[
                        'street' => $address->street,
                        'cross_street1' => $address->cross_street1,
                        'cross_street2' => $address->cross_street2,
                        'house_number'=> $address->house_number,
                        'suburb' => $address->suburb,
                        'postcode' => $address->postcode
                    ],
                    'link'=>[
                        'self'=>$body['data']['house']['link']['self']
                    ],
                    'User'=>[
                        'data'=>[
                            'name'=>$host->name,
                            'user'=> $host->user,
                            'birthdate' => $host->birthdate,
                            'cellphone' => $host->cellphone,
                            'email' => $host->email
                        ],
                        'link'=>[
                            'self' => $body['data']['house']['User']['link']['self']
                        ]
                    ]
                ],
                'traveler'=>[
                    'id'=> $traveler->id,
                    'data'=>[
                        'name'=>$traveler->name,
                        'user'=>$traveler->user,
                        'birthdate'=>$traveler->birthdate,
                        'cellphone'=>$traveler->cellphone,
                        'email' => $traveler->email,
                    ],
                    'link'=>[
                        'self'=> $body['data']['traveler']['link']['self']
                    ]
                ],
                'link'=>[
                    'self' => $body['data']['link']['self']
                ]
            ]
        ]);
    }

    public function test_traveler_can_update_a_booking()
    {
        $booking = factory(BookingHouse::class)->create([
            "check_in"=>"2019-12-25 16:00:00",
            "check_out"=>"2019-12-26 16:00:00"]);
        $house = House::findorfail($booking['house_id']);
        $address = Address::find($house->address_id);
        $traveler = User::findorfail($booking['user_id']);
        $host = User::findorfail($house['user_id']);
        $bookingData = [
            "data" => [
                "attributes"=>[
                    "check_in"=>"2019-12-26 15:00:00",
                    "check_out"=>"2019-12-26 16:00:00"
                ]
            ]
        ];
        $response = $this->actingAs($host)
        ->json('PUT', '/api/v1/bookings/'.$booking['id'].'?api_token='.$traveler['api_token'],$bookingData);
        $response->assertStatus(200); 
        $response->assertJsonStructure([
            'data'=> [
                'type',
                'id',
                'user_id',
                'house_id',
                'attributes' => [
                    'check_in',
                    'check_out',
                    'status'
                ],
                'house' => [
                    'id',
                    'id_user',
                    'data'=>[
                        'description',
                        'price_for_day',
                        'status',
                        'state',
                        'municipality',
                    ],
                    'address'=>[
                        'street',
                        'cross_street1',
                        'cross_street2',
                        'house_number',
                        'suburb',
                        'postcode'
                    ],
                    'link'=>[
                        'self'
                    ],
                    'User'=>[
                        'data'=>[
                            'name',
                            'user',
                            'birthdate',
                            'cellphone',
                            'email',
                        ],
                        'link'=>[
                            'self'
                        ]
                    ]
                ],
                'traveler'=>[
                    'id',
                    'data'=>[
                        'name',
                        'user',
                        'birthdate',
                        'cellphone',
                        'email',
                    ],
                    'link'=>[
                        'self'
                    ]
                ],
                'link'=>[
                    'self'
                ]
            ]
        ]);

        $body = $response->decodeResponseJson();

        $response->assertJsonFragment([
            'data'=> [
                'type' => 'bookings house',
                'id' => $body['data']['id'],
                'user_id' => $traveler->id,
                'house_id' => $house->id,
                'attributes' => [
                    'check_in' => '2019-12-26 15:00:00',
                    'check_out' => '2019-12-26 16:00:00',
                    'status' => $body['data']['attributes']['status'],
                ],
                'house' => [
                    'id' => $house->id,
                    'id_user'=> $house->user_id,
                    'data'=>[
                        'description'=> $house->description,
                        'price_for_day'=> "$house->price_for_day",
                        'status' => $house->status,
                        'state' => $house->state,
                        'municipality' => $house->municipality,
                    ],
                    'address'=>[
                        'street' => $address->street,
                        'cross_street1' => $address->cross_street1,
                        'cross_street2' => $address->cross_street2,
                        'house_number'=> $address->house_number,
                        'suburb' => $address->suburb,
                        'postcode' => $address->postcode
                    ],
                    'link'=>[
                        'self'=>$body['data']['house']['link']['self']
                    ],
                    'User'=>[
                        'data'=>[
                            'name'=>$host->name,
                            'user'=> $host->user,
                            'birthdate' => $host->birthdate,
                            'cellphone' => $host->cellphone,
                            'email' => $host->email
                        ],
                        'link'=>[
                            'self' => $body['data']['house']['User']['link']['self']
                        ]
                    ]
                ],
                'traveler'=>[
                    'id'=> $traveler->id,
                    'data'=>[
                        'name'=>$traveler->name,
                        'user'=>$traveler->user,
                        'birthdate'=>$traveler->birthdate,
                        'cellphone'=>$traveler->cellphone,
                        'email' => $traveler->email,
                    ],
                    'link'=>[
                        'self'=> $body['data']['traveler']['link']['self']
                    ]
                ],
                'link'=>[
                    'self' => $body['data']['link']['self']
                ]
            ]
        ]);
    }

    public function test_host_can_not_update_a_booking_accepted()
    {
        $booking = factory(BookingHouse::class)->create([
            "check_in"=>"2019-12-25 16:00:00",
            "check_out"=>"2019-12-26 16:00:00",
            "status"=>"accepted"]);
        $house = House::findorfail($booking['house_id']);
        $host = User::findorfail($house['user_id']);
        $bookingData = [
            "data" => [
                "attributes"=>[
                    "status"=>"rejected"
                ]
            ]
        ];
        $response = $this->actingAs($host)
        ->json('PUT', '/api/v1/bookings/'.$booking['id'].'?api_token='.$host['api_token'],$bookingData);
        $response->assertStatus(403); 
        
        $response->assertJsonStructure([
            'errors'=>[
                'code',
                'title',
                'message'
            ]
        ]);
         
        $response->assertJsonFragment([
            'errors' => [
                'code' => 'ERROR-3',
                'title' => 'FORBIDDEN',
                'message' =>"Acción no autorizada, no puede cambiar el estado de una renta aceptada"
            ]
        ]);
    }
    public function test_traveler_update_a_booking_accepted()
    {
        $booking = factory(BookingHouse::class)->create([
            "check_in"=>"2019-12-25 16:00:00",
            "check_out"=>"2019-12-26 16:00:00",
            "status"=>"accepted"]);
        $house = House::findorfail($booking['house_id']);
        $address = Address::find($house->address_id);
        $traveler = User::findorfail($booking['user_id']);
        $host = User::findorfail($house['user_id']);
        $bookingData = [
            "data" => [
                "attributes"=>[
                    "check_in"=>"2019-12-26 15:00:00",
                    "check_out"=>"2019-12-26 16:00:00"
                ]
            ]
        ];
        $response = $this->actingAs($host)
        ->json('PUT', '/api/v1/bookings/'.$booking['id'].'?api_token='.$traveler['api_token'],$bookingData);
        $response->assertStatus(201); 
        $response->assertJsonStructure([
            'data'=> [
                'type',
                'id',
                'user_id',
                'house_id',
                'attributes' => [
                    'check_in',
                    'check_out',
                    'status'
                ],
                'house' => [
                    'id',
                    'id_user',
                    'data'=>[
                        'description',
                        'price_for_day',
                        'status',
                        'state',
                        'municipality',
                    ],
                    'address'=>[
                        'street',
                        'cross_street1',
                        'cross_street2',
                        'house_number',
                        'suburb',
                        'postcode'
                    ],
                    'link'=>[
                        'self'
                    ],
                    'User'=>[
                        'data'=>[
                            'name',
                            'user',
                            'birthdate',
                            'cellphone',
                            'email',
                        ],
                        'link'=>[
                            'self'
                        ]
                    ]
                ],
                'traveler'=>[
                    'id',
                    'data'=>[
                        'name',
                        'user',
                        'birthdate',
                        'cellphone',
                        'email',
                    ],
                    'link'=>[
                        'self'
                    ]
                ],
                'link'=>[
                    'self'
                ]
            ]
        ]);

        $body = $response->decodeResponseJson();

        $response->assertJsonFragment([
            'data'=> [
                'type' => 'bookings house',
                'id' => $body['data']['id'],
                'user_id' => $traveler->id,
                'house_id' => $house->id,
                'attributes' => [
                    'check_in' => '2019-12-26 15:00:00',
                    'check_out' => '2019-12-26 16:00:00',
                    'status' =>  $body['data']['attributes']['status']
                ],
                'house' => [
                    'id' => $house->id,
                    'id_user'=> $house->user_id,
                    'data'=>[
                        'description'=> $house->description,
                        'price_for_day'=> "$house->price_for_day",
                        'status' => $house->status,
                        'state' => $house->state,
                        'municipality' => $house->municipality,
                    ],
                    'address'=>[
                        'street' => $address->street,
                        'cross_street1' => $address->cross_street1,
                        'cross_street2' => $address->cross_street2,
                        'house_number'=> $address->house_number,
                        'suburb' => $address->suburb,
                        'postcode' => $address->postcode
                    ],
                    'link'=>[
                        'self'=>$body['data']['house']['link']['self']
                    ],
                    'User'=>[
                        'data'=>[
                            'name'=>$host->name,
                            'user'=> $host->user,
                            'birthdate' => $host->birthdate,
                            'cellphone' => $host->cellphone,
                            'email' => $host->email
                        ],
                        'link'=>[
                            'self' => $body['data']['house']['User']['link']['self']
                        ]
                    ]
                ],
                'traveler'=>[
                    'id'=> $traveler->id,
                    'data'=>[
                        'name'=>$traveler->name,
                        'user'=>$traveler->user,
                        'birthdate'=>$traveler->birthdate,
                        'cellphone'=>$traveler->cellphone,
                        'email' => $traveler->email,
                    ],
                    'link'=>[
                        'self'=> $body['data']['traveler']['link']['self']
                    ]
                ],
                'link'=>[
                    'self' => $body['data']['link']['self']
                ]
            ]
        ]);      
       
    }
    
    public function test_user_can_not_update_booking_that_is_not_his(){
        $booking = factory(BookingHouse::class)->create();
        $otherUser = factory(User::class)->create();
        $bookingData = [
            "data" => [
                "attributes"=>[
                    "check_in"=>"2019-12-26 15:00:00",
                    "check_out"=>"2019-12-26 16:00:00"
                ]
            ]
        ];
        $response = $this->actingAs($otherUser)
        ->json('PUT', '/api/v1/bookings/'.$booking['id'].'?api_token='.$otherUser['api_token'],$bookingData);
        $response->assertStatus(403); 
        
        $response->assertJsonStructure([
            'errors'=>[
                'code',
                'title',
                'message'
            ]
        ]);
         
        $response->assertJsonFragment([
            'errors' => [
                'code' => 'ERROR-3',
                'title' => 'FORBIDDEN',
                'message' =>"Acción no autorizada, las reservaciones son privadas, no puede actualizar este booking"
            ]
        ]);
    }

    public function test_user_can_not_update_a_booking_rejected()
    {
        $booking = factory(BookingHouse::class)->create([
            "check_in"=>"2019-12-25 16:00:00",
            "check_out"=>"2019-12-26 16:00:00",
            "status"=>"rejected"]);
        $traveler = User::findorfail($booking['user_id']);
        $bookingData = [
            "data" => [
                "attributes"=>[
                    "check_in"=>"2019-12-28 16:00:00",
                    "check_out"=>"2019-12-29 16:00:00",
                    "status"=>"canceled"
                ]
            ]
        ];
        $response = $this->actingAs($traveler)
        ->json('PUT', '/api/v1/bookings/'.$booking['id'].'?api_token='.$traveler['api_token'],$bookingData);
        $response->assertStatus(403); 
        
        $response->assertJsonStructure([
            'errors'=>[
                'code',
                'title',
                'message'
            ]
        ]);
         
        $response->assertJsonFragment([
            'errors' => [
                'code' => 'ERROR-3',
                'title' => 'FORBIDDEN',
                'message' =>"Acción no autorizada, la renta se rechazo no puedes cambiar el estado"
            ]
        ]);
    }

    public function test_user_can_not_update_a_booking_canceled()
    {
        $booking = factory(BookingHouse::class)->create([
            "check_in"=>"2019-12-25 16:00:00",
            "check_out"=>"2019-12-26 16:00:00",
            "status"=>"canceled"]);
        $house = House::findorfail($booking['house_id']);
        $host = User::findorfail($house['user_id']);
        $bookingData = [
            "data" => [
                "attributes"=>[
                    "status"=>"accepted"
                ]
            ]
        ];
        $response = $this->actingAs($host)
        ->json('PUT', '/api/v1/bookings/'.$booking['id'].'?api_token='.$host['api_token'],$bookingData);
        $response->assertStatus(403); 
        
        $response->assertJsonStructure([
            'errors'=>[
                'code',
                'title',
                'message'
            ]
        ]);
         
        $response->assertJsonFragment([
            'errors' => [
                'code' => 'ERROR-3',
                'title' => 'FORBIDDEN',
                'message' =>"Acción no autorizada, la renta se cancelo no puedes cambiar el estado"
            ]
        ]);
    }

    public function test_user_can_not_update_a_booking_with_previous_dates()
    {
        $booking = factory(BookingHouse::class)->create([
            "check_in"=>"2018-12-25 16:00:00",
            "check_out"=>"2018-12-26 16:00:00"]);
        $house = House::findorfail($booking['house_id']);
        $host = User::findorfail($house['user_id']);
        $bookingData = [
            "data" => [
                "attributes"=>[
                    "status"=>"accepted"
                ]
            ]
        ];
        $response = $this->actingAs($host)
        ->json('PUT', '/api/v1/bookings/'.$booking['id'].'?api_token='.$host['api_token'],$bookingData);
        $response->assertStatus(403); 
        
        $response->assertJsonStructure([
            'errors'=>[
                'code',
                'title',
                'message'
            ]
        ]);
         
        $response->assertJsonFragment([
            'errors' => [
                'code' => 'ERROR-3',
                'title' => 'FORBIDDEN',
                'message' =>"Acción no autorizada, No se puede actualizar reservaciones de fechas pasadas"
            ]
        ]);
    }

    public function test_traveler_can_not_canceled_after_three_days_in_advance()
    {

        $booking = factory(BookingHouse::class)->create([
            "check_in"=>now()->addDay(),
            "check_out"=>now()->addDay(),
            "status" => "accepted" ]);
        $traveler = User::findorfail($booking['user_id']);
        $bookingData = [
            "data" => [
                "attributes"=>[
                    "check_in"=>now(),
                    "check_out"=>now()->addDay(),
                    "status"=>"canceled"
                ]
            ]
        ];
        $response = $this->actingAs($traveler)
        ->json('PUT', '/api/v1/bookings/'.$booking['id'].'?api_token='.$traveler['api_token'],$bookingData);
        $response->assertStatus(403); 
        
        $response->assertJsonStructure([
            'errors'=>[
                'code',
                'title',
                'message'
            ]
        ]);
         
        $response->assertJsonFragment([
            'errors' => [
                'code' => 'ERROR-3',
                'title' => 'FORBIDDEN',
                'message' =>"Acción no autorizada, El tiempo de cancelación ha pasado, ya no puede cancelar"
            ]
        ]);
    }

    public function test_traveler_can_not_accept_your_booking()
    {

        $booking = factory(BookingHouse::class)->create([
            "check_in"=>now()->addDay(),
            "check_out"=>now()->addDay(),
            "status" => "accepted" ]);
        $traveler = User::findorfail($booking['user_id']);
        $bookingData = [
            "data" => [
                "attributes"=>[
                    "check_in"=>now(),
                    "check_out"=>now()->addDay(),
                    "status"=>"accepted"
                ]
            ]
        ];
        $response = $this->actingAs($traveler)
        ->json('PUT', '/api/v1/bookings/'.$booking['id'].'?api_token='.$traveler['api_token'],$bookingData);
        $response->assertStatus(422); 
        
        $response->assertJsonStructure([
            'errors'=>[
                'code',
                'title',
                'message'
            ]
        ]);
         
        $response->assertJsonFragment([
            'errors' => [
                'code' => 'ERROR-1',
                'title' => 'Unprocessable Entity',
                'message' =>[
                    'data.attributes.status' => [
                        "El status solo puede ser cancelado"
                    ]
                ]
            ]
        ]);
    }

}
