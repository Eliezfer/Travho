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
     * DELETE-1
     */
    public function test_user_can_delete_a_house(){

        $user = factory(User::class)->create();
        $address=factory(Address::class)->create();
        $house= factory(House::class)->create([
            'user_id'=>$user['id'],
            'address_id'=>$address['id']
        ]);
        $response= $this->actingAs($user)->DELETE('/api/v1/houses/'.$house['id'].'?api_token='.$user['api_token']);
        $response->assertStatus(204);
        $this->assertDatabaseMissing(
            'houses'
           ,[
               'id'=>$house['id'],
               'user_id'=>$house['id_user'],
               'address_id'=>$house['id'],
               'description'=>$house['description'],
               'price_for_day'=>$house['price_for_day'],
               'status'=>$house['status'],

               'state'=>$house['state'],
               'municipality'=>$house['municipality'],
           ]);
           $this->assertDatabaseHas(
               'addresses'
               ,[
                   'id'=>$address['id'],
                   'street'=>$address['street'],
                   'cross_street1'=>$address['cross_street1'],
                   'cross_street2'=>$address['cross_street2'],
                   'house_number'=>$address['house_number'],
                   'suburb'=>$address['suburb'],
                   'postcode'=>$address['postcode']
           ]);
    }
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
