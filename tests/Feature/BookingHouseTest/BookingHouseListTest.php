<?php

namespace Tests\Feature\BookingTest;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\BookingHouse;
use App\House;
use App\Address;
use App\User;

class BookingHouseListTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_traveler_can_list_your_bookings()
    {
        $user = factory(User::class)->create();        
        $bookings= factory(BookingHouse::class,10)->create(
            ["user_id" => $user['id']]
        )->map(function ($booking) {
            return $booking->only(['id', 'user_id', 'house_id','check_in','check_out']);
        });;
        $response = $this->actingAs($user)
        ->json('GET', '/api/v1/bookings'.'?api_token='.$user['api_token']);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data'=> [
                '*' => ['type',
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
                ]]
            ],
            'links'=> [
                'first',
                'last',
                'prev',
                'next'
            ],
              'meta' => [
                'current_page',
                'from',
                'last_page',
                'path',
                'per_page',
                'to',
                'total'
              ]
        ]);

    }

    public function test_host_can_list_bookings_from_your_house()
    {
        $house = factory(House::class)->create();
        $host = User::find($house->user_id);

        $bookings= factory(BookingHouse::class,10)->create(
            ["house_id" => $house['id']]
        )->map(function ($booking) {
            return $booking->only(['id', 'user_id', 'house_id','check_in','check_out']);
        });;
        $response = $this->actingAs($host)
        ->json('GET', '/api/v1/bookingsFromMyHouse'.'?api_token='.$host['api_token']);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data'=> [
                '*' => ['type',
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
                ]]
            ],
            'links'=> [
                'first',
                'last',
                'prev',
                'next'
            ],
              'meta' => [
                'current_page',
                'from',
                'last_page',
                'path',
                'per_page',
                'to',
                'total'
              ]
        ]);

    }

    public function test_client_empty_list_bookings()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)
        ->json('GET', '/api/v1/bookings'.'?api_token='.$user['api_token']);
        $response->assertStatus(200);
        $response->assertJsonStructure([
        'data'=>[],
        'links'=> [
            'first',
            'last',
            'prev',
            'next'
        ],
          'meta' => [
            'current_page',
            'from',
            'last_page',
            'path',
            'per_page',
            'to',
            'total'
          ]
        ]);

    }

}
