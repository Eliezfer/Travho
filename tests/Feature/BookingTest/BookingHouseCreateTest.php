<?php

namespace Tests\Feature\BookingTest;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\House;
use App\User;

class BookinHouseCreateTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    

    public function test_client_can_create_a_Booking(){
        //dado
        $userTraveled = factory(User::class)->create();
        $house = factory(House::class)->create();
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

        $response->assertJsonStructure([
            'data '=> [
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
                            'password',
                            'birthdate',
                            'cellphone',
                            'email',
                        ],
                        'link'=>[
                            'self'
                        ]
                    ]
                ],//add user
            ]
        ]);
        $body = $response->decodeResponseJson();
        //$userTraveled = factory(User::class)->create();
    }

}
