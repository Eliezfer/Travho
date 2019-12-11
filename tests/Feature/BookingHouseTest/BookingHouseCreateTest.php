<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\BookingHouse;
use App\House;
use App\Address;
use App\User;

class BookinHouseCreateTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    

    public function test_client_can_create_a_booking(){
        //dado
        $userTraveled = factory(User::class)->create();
        $house = factory(House::class)->create();
        $address = Address::find($house->address_id);
        $userHost = User::find($house->user_id);
        $bookingData = [
            "data" => [
                "type" => "bookings house",
                "attributes" => [
                    "house_id"=>$house->id,
                    "check_in"=>"2019-12-25 15:00:00",
                    "check_out"=>"2019-12-25 16:00:00"
                ]
            ]
        ];
        $response = $this->actingAs($userTraveled)->json('POST', '/api/v1/bookings?api_token='.$userTraveled['api_token'], $bookingData);
        $response->assertStatus(201);
        $body = $response->decodeResponseJson();
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
                        'country',
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
                'user'=>[
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
                'user_id' => $userTraveled->id,
                'house_id' => $house->id,
                'attributes' => [
                    'check_in' => '2019-12-25 15:00:00',
                    'check_out' => '2019-12-25 16:00:00',
                    'status' => 'in process'
                ],
                'house' => [
                    'id' => $house->id,
                    'id_user'=> $house->user_id,
                    'data'=>[
                        'description'=> $house->description,
                        'price_for_day'=> "$house->price_for_day",
                        'status' => $house->status,
                        'country' => $house->country,
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
                            'name'=>$userHost->name,
                            'user'=> $userHost->user,
                            'birthdate' => $userHost->birthdate,
                            'cellphone' => $userHost->cellphone,
                            'email' => $userHost->email
                        ],
                        'link'=>[
                            'self' => $body['data']['house']['User']['link']['self']
                        ]
                    ]
                ],
                'user'=>[
                    'id'=> $userTraveled->id,
                    'data'=>[
                        'name'=>$userTraveled->name,
                        'user'=>$userTraveled->user,
                        'birthdate'=>$userTraveled->birthdate,
                        'cellphone'=>$userTraveled->cellphone,
                        'email' => $userTraveled->email,
                    ],
                    'link'=>[
                        'self'=> $body['data']['user']['link']['self']
                    ]
                ],
                'link'=>[
                    'self' => $body['data']['link']['self']
                ]
            ]
        ]);
        
        $this->assertDatabaseHas(
            'booking_houses',
            [
                'id' => $body['data']['id'],
                'user_id' => $userTraveled->id,
                'house_id' => $house->id,
                'check_in' => '2019-12-25 15:00:00',
                'check_out' => '2019-12-25 16:00:00',
                'status' => 'in process'
            ]
        );
        //$userTraveled = factory(User::class)->create();
    }

    public function test_client_error_house_id_wasnt_sent_when_creating_a_booking()
    {
        $userTraveled = factory(User::class)->create();
        $bookingData = [
            "data" => [
                "type" => "bookings house",
                "attributes" => [
                    "check_in"=>"2019-12-25 15:00:00",
                    "check_out"=>"2019-12-25 16:00:00"
                ]
            ]
        ];
        $response = $this->actingAs($userTraveled)->json('POST', '/api/v1/bookings?api_token='.$userTraveled['api_token'], $bookingData);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'errors'=>[
                'code',
                'title',
                'message'=>[
                    'data.attributes.house_id'
                ]
            ]
        ]);

         // Assert the error
        $response->assertJsonFragment([
            'errors' => [
                'code' => 'ERROR-1',
                'title' => 'Unprocessable Entity',
                'message' =>[
                    'data.attributes.house_id' => [
                        "El id de la casa no es enviado en la solicitud"
                    ]
                ]
            ]
        ]);
    }

    public function test_client_error_check_in_wasnt_sent_when_creating_a_booking()
    {
        $userTraveled = factory(User::class)->create();
        $house = factory(House::class)->create();
        $userHost = User::find($house->user_id);
        $bookingData = [
            "data" => [
                "type" => "bookings house",
                "attributes" => [
                    "house_id"=>$house->id,
                    "check_out"=>"2019-12-25 16:00:00"
                ]
            ]
        ];
        $response = $this->actingAs($userTraveled)->json('POST', '/api/v1/bookings?api_token='.$userTraveled['api_token'], $bookingData);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'errors'=>[
                'code',
                'title',
                'message'=>[
                    'data.attributes.check_in'
                ]
            ]
        ]);

         // Assert the error
        $response->assertJsonFragment([
            'errors' => [
                'code' => 'ERROR-1',
                'title' => 'Unprocessable Entity',
                'message' =>[
                    'data.attributes.check_in' => [
                        "La fecha de entrada no es enviado en la solicitud"
                    ]
                ]
            ]
        ]);
    }

    public function test_client_error_check_out_wasnt_sent_when_creating_a_booking()
    {
        $userTraveled = factory(User::class)->create();
        $house = factory(House::class)->create();
        $userHost = User::find($house->user_id);
        $bookingData = [
            "data" => [
                "type" => "bookings house",
                "attributes" => [
                    "house_id"=>$house->id,
                    "check_in"=>"2019-12-25 16:00:00"
                ]
            ]
        ];
        $response = $this->actingAs($userTraveled)->json('POST', '/api/v1/bookings?api_token='.$userTraveled['api_token'], $bookingData);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'errors'=>[
                'code',
                'title',
                'message'=>[
                    'data.attributes.check_in',
                    'data.attributes.check_out'
                ]
            ]
        ]);

         // Assert the error
        $response->assertJsonFragment([
            'errors' => [
                'code' => 'ERROR-1',
                'title' => 'Unprocessable Entity',
                'message' =>[
                    'data.attributes.check_in' => [
                        "La fecha de entrada debe ser una fecha antes del check_out"
                    ],
                    'data.attributes.check_out' => [
                        "La fecha de salida no es enviado en la solicitud"
                    ]
                ]
            ]
        ]);
    }

    public function test_client_error_when_check_in_is_after_check_out()
    {
        $userTraveled = factory(User::class)->create();
        $house = factory(House::class)->create();
        $userHost = User::find($house->user_id);
        $bookingData = [
            "data" => [
                "type" => "bookings house",
                "attributes" => [
                    "house_id"=>$house->id,
                    "check_in"=>"2019-12-25 16:00:00",
                    "check_out"=>"2019-12-24 16:00:00"
                ]
            ]
        ];
        $response = $this->actingAs($userTraveled)->json('POST', '/api/v1/bookings?api_token='.$userTraveled['api_token'], $bookingData);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'errors'=>[
                'code',
                'title',
                'message'=>[
                    'data.attributes.check_in',
                    'data.attributes.check_out'
                ]
            ]
        ]);

         // Assert the error
        $response->assertJsonFragment([
            'errors' => [
                'code' => 'ERROR-1',
                'title' => 'Unprocessable Entity',
                'message' =>[
                    'data.attributes.check_in' => [
                        "La fecha de entrada debe ser una fecha antes del check_out"
                    ],
                    'data.attributes.check_out' => [
                        "La fecha de salida debe ser una fecha despues del check_in"
                    ]
                ]
            ]
        ]);
    }

    public function test_client_error_when_house_not_exist()
    {
        $userTraveled = factory(User::class)->create();
        $bookingData = [
            "data" => [
                "type" => "bookings house",
                "attributes" => [
                    "house_id"=>"1",
                    "check_in"=>"2019-12-25 16:00:00",
                    "check_out"=>"2019-12-26 16:00:00"
                ]
            ]
        ];
        $response = $this->actingAs($userTraveled)->json('POST', '/api/v1/bookings?api_token='.$userTraveled['api_token'], $bookingData);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'errors'=>[
                'code',
                'title',
                'message'=>[
                    'data.attributes.house_id'
                ]
            ]
        ]);

         // Assert the error
        $response->assertJsonFragment([
            'errors' => [
                'code' => 'ERROR-1',
                'title' => 'Unprocessable Entity',
                'message' =>[
                    'data.attributes.house_id' => [
                        "El id de la casa no existe"
                    ]
                ]
            ]
        ]);
    }

    public function test_client_error_when_host_try_booking_your_own_house()
    {
        $house = factory(House::class)->create();
        $userHost = User::findorfail($house['user_id']);
        $bookingData = [
            "data" => [
                "type" => "bookings house",
                "attributes" => [
                    "house_id"=>$house->id,
                    "check_in"=>"2019-12-25 15:00:00",
                    "check_out"=>"2019-12-25 16:00:00"
                ]
            ]
        ];
        $response = $this->actingAs($userHost)->json('POST', '/api/v1/bookings?api_token='.$userHost['api_token'], $bookingData);
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
                'message' =>"Acción no autorizada, no puede rentar su propia casa"
            ]
        ]);
    }

    public function test_client_error_when_house_not_vailable()
    {
        $userTraveled = factory(User::class)->create();
        $house = factory(House::class)->create();
        $house['status']='false';
        $house->save();
        $userHost = User::find($house->user_id);

        $userTraveled = factory(User::class)->create();
        $bookingData = [
            "data" => [
                "type" => "bookings house",
                "attributes" => [
                    "house_id"=>$house->id,
                    "check_in"=>"2019-12-25 16:00:00",
                    "check_out"=>"2019-12-26 16:00:00"
                ]
            ]
        ];
        $response = $this->actingAs($userTraveled)->json('POST', '/api/v1/bookings?api_token='.$userTraveled['api_token'], $bookingData);
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
                'message' =>"Acción no autorizada, no puede rentar una casa dada de baja"
            ]
        ]);
    }

    public function test_client_error_when_house_is_booking()
    {
        $userTraveled = factory(User::class)->create();

        $bookingAccept = factory(BookingHouse::class)->create([
            "check_in"=>"2019-12-25 16:00:00",
            "check_out"=>"2019-12-26 16:00:00",
            "status" => "accepted"]);

        $house = House::findorfail($bookingAccept['house_id']);

        $userTraveled = factory(User::class)->create();
        $bookingData = [
            "data" => [
                "type" => "bookings house",
                "attributes" => [
                    "house_id"=>$house->id,
                    "check_in"=>"2019-12-25 16:00:00",
                    "check_out"=>"2019-12-26 16:00:00"
                ]
            ]
        ];
        $response = $this->actingAs($userTraveled)->json('POST', '/api/v1/bookings?api_token='.$userTraveled['api_token'], $bookingData);
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
                'message' =>"Acción no autorizada, la casa ha sido rentada en esas fechas"
            ]
        ]);
    }

    public function test_unauthenticated_client(){
        $userTraveled = factory(User::class)->create();
        $house = factory(House::class)->create();
        $bookingData = [
            "data" => [
                "type" => "bookings house",
                "attributes" => [
                    "house_id"=>$house->id,
                    "check_in"=>"2019-12-25 15:00:00",
                    "check_out"=>"2019-12-25 16:00:00"
                ]
            ]
        ];
        $response = $this->actingAs($userTraveled)->json('POST', '/api/v1/bookings', $bookingData);
        $response->assertStatus(401);
        $response->assertJsonStructure([
            'errors'=>[
                'code',
                'title',
                'message'
            ]
        ]);
         
        $response->assertJsonFragment([
            'errors' => [
                'code' => 'ERROR-4',
                'title' => 'UNAUTHORIZED',
                'message' =>"Consulte Autenticación de acceso básica y Autenticación de acceso resumido"
            ]
        ]);
        
    }

}
