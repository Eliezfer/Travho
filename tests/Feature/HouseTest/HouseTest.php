<?php

namespace Tests\Feature\HouseTest;

use App\House;
use App\Address;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HouseTest extends TestCase
{
    use RefreshDatabase;




    /**
     * LIST-1
     */
    public function test_user_can_show_a_list_of_houses(){

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

        $response->assertJsonStructure(['data'=>['*'=>[
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
        ]]]);
    }
    /**
     * LIST-2
     */
    public function test_user_can_show_a_list_without_houses(){


        $response= $this->GET('/api/v1/houses/');

        $response->assertStatus(200);

        $response->assertJsonStructure(['data'=>[]]);
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
