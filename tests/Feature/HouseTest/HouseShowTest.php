<?php

namespace Tests\Feature\HouseTest;

use App\House;
use App\Address;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HouseShowTest extends TestCase
{

    use RefreshDatabase;
     /**
     * SHOW-1
     */
    public function test_user_can_show_a_house()
    {
        $user = factory(User::class)->create();
        $address=factory(Address::class)->create();
        $house= factory(House::class)->create([
            'user_id'=>$user['id'],
            'address_id'=>$address['id']
        ]);

        $response= $this->GET('/api/v1/houses/'.$house['id']);

        $response->assertStatus(200);

        $body = $response->decodeResponseJson();
        $response->assertJson([
            'id'=>$house['id'],
            'id_user'=>$house['user_id'],
            'data'=>[
                'description'=>$house['description'],
                'price_for_day'=>$house['price_for_day'],
                'status'=>$house['status'],

                'state'=>$house['state'],
                'municipality'=>$house['municipality'],
            ],
            'address'=>[
                'street'=>$address['street'],
                'cross_street1'=>$address['cross_street1'],
                'cross_street2'=>$address['cross_street2'],
                'house_number'=>$address['house_number'],
                'suburb'=>$address['suburb'],
                'postcode'=>$address['postcode']
            ],
            'link'=>[
                'self'=>$body['link']['self']
            ],
            'User'=>[
                'data'=>[
                    'name'=>$user['name'],
                    'user'=>$user['user'],
                    'birthdate'=>$user['birthdate'],
                    'cellphone'=>$user['cellphone'],
                    'email'=>$user['email'],
                ],
                'link'=>[
                    'self'=>$body['User']['link']['self'],
                ]
            ]
        ]);



    }
        /**
     * SHOW-1
     */
    public function test_user_can_show_a_house_id_not_exist(){
        $user = factory(User::class)->create();
        $address=factory(Address::class)->create();
        $house= factory(House::class)->create([
            'user_id'=>$user['id'],
            'address_id'=>$address['id']
        ]);
                    //generamos una id inexistente
        $id=$house['id']+1;
        $response= $this->GET('/api/v1/houses/'.$id);

        $response->assertStatus(404);

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
