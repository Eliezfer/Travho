<?php

namespace Tests\Feature\HouseTest;
use App\House;
use App\Address;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HouseListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * LIST-1
     */
    public function test_user_can_show_a_list_of_houses()
    {

        $user = factory(User::class,2)->create();
        $address=factory(Address::class,2)->create();
        $house= factory(House::class)->create([
            'user_id'=>$user[0]['id'],
            'address_id'=>$address[0]['id']
        ]);
        $house= factory(House::class)->create([
            'user_id'=>$user[1]['id'],
            'address_id'=>$address[1]['id']
        ]);
        $response= $this->GET('/api/v1/houses/');

        $response->assertStatus(200);

        $response->assertJsonStructure(
        [
        'data'=>[
                '*'=>[
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
                ]
            ],
        'links'=> [
            'first',
            'last',
            'prev',
            'next',
        ],
        'meta'=> [
            'current_page',
            'from',
            'last_page',
            'path',
            'per_page',
            'to',
            'total',
        ]
        ]);
    }
    /**
     * LIST-2
     */
    public function test_user_can_show_a_list_without_houses()
    {
        $response= $this->GET('/api/v1/houses/');

        $response->assertStatus(200);

        $response->assertJsonStructure(['data'=>[]]);
    }
    /**
     * LIST-3
     */
    public function test_user_can_show_a_list_of_houses_by_state()
    {

        $user = factory(User::class)->create();
        $house= factory(House::class,10)->create([
            'user_id'=>$user['id'],
            'state'=>'Yucatán',
        ]);

        $response= $this->GET('/api/v1/houses/?state=Yucatán');

        $response->assertStatus(200);

        $response->assertJsonStructure(
        [
        'data'=>[
                '*'=>[
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
                ]
            ],
        'links'=> [
            'first',
            'last',
            'prev',
            'next',
        ],
        'meta'=> [
            'current_page',
            'from',
            'last_page',
            'path',
            'per_page',
            'to',
            'total',
        ]
        ]);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
