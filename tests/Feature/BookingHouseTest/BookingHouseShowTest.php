<?php

namespace Tests\Feature\BookingTest;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\BookingHouse;
use App\House;
use App\Address;
use App\User;

class BookingHouseShowTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_traveler_can_show_your_booking()
    {
        $booking = factory(BookingHouse::class)->create([
            "check_in"=>"2019-12-25 16:00:00",
            "check_out"=>"2019-12-26 16:00:00"]);
        $traveler = User::findorfail($booking['user_id']);
        $house = House::findorfail($booking['house_id']);
        $address = Address::find($house->address_id);
        $host = User::find($house->user_id);
        $response = $this->actingAs($traveler)
        ->json('GET', '/api/v1/bookings/'.$booking['id'].'?api_token='.$traveler['api_token']);
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
                    'status' => $body['data']['attributes']['status']
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

    public function test_host_can_show_your_booking()
    {
        $booking = factory(BookingHouse::class)->create([
            "check_in"=>"2019-12-25 16:00:00",
            "check_out"=>"2019-12-26 16:00:00"]);
        $traveler = User::findorfail($booking['user_id']);
        $house = House::findorfail($booking['house_id']);
        $address = Address::find($house->address_id);
        $host = User::find($house->user_id);
        $response = $this->actingAs($host)
        ->json('GET', '/api/v1/bookings/'.$booking['id'].'?api_token='.$host['api_token']);
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
                    'status' => $body['data']['attributes']['status']
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

    public function test_other_user_can_not_show_a_booking_of_other_user()
    {
        $booking = factory(BookingHouse::class)->create();
        $newUser = factory(User::class)->create();
        $response = $this->actingAs($newUser)
        ->json('GET', '/api/v1/bookings/'.$booking['id'].'?api_token='.$newUser['api_token']);
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
                'message' =>"Acción no autorizada, las reservaciones son privadas, no puede ver este booking"
            ]
        ]);

    }

    public function test_client_error_not_found_show_a_booking()
    {
        $newUser = factory(User::class)->create();
        $response = $this->actingAs($newUser)
        ->json('GET', '/api/v1/bookings/1'.'?api_token='.$newUser['api_token']);
        $response->assertStatus(404);
        $response->assertJsonStructure([
            'errors'=>[
                'code',
                'title',
                'message'
            ]
        ]);

         // Assert the error
        $response->assertJsonFragment([
            'errors' => [
                'code' => 'ERROR-2',
                'title' => 'NOT FOUND',
                'message' => "No se encontro el recurso"
            ]
        ]);
    }

}
