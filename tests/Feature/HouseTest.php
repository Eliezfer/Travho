<?php

namespace Tests\Feature;

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
     * CREATE-1
     */
    public function test_user_can_create_a_house(){
        //dado
        $user = factory(User::class)->create();
     $houseData =[
            "data"=>[
              "description"=> "new house",
              "price_for_day"=> "22",
              "status"=> "true",
              "country"=> "mexico",
              "state"=> "Yucatán",
              "municipality"=> "Merida"
            ]
              ,
            "address"=> [
              "street"=> "29",
              "cross_street1"=> "31",
              "cross_street2"=> "33",
              "house_number"=> "23",
              "suburb"=> "centro",
              "postcode"=> "97000"
            ]
            ];
             //cuando
        $response = $this->actingAs($user)->json('POST', '/api/houses?api_token='.$user['api_token'], $houseData);
            //entonces
        $body = $response->decodeResponseJson();
        $response->assertStatus(201);
        //verificar la estructura devuelta
        $response->assertJsonStructure([
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
        ]);
        $response->assertJson([

                'id'=>$body['id'],
                'id_user'=>$body['id_user'],
                'data'=>[
                    'description'=>$body['data']['description'],
                    'price_for_day'=>$body['data']['price_for_day'],
                    'status'=>$body['data']['status'],
                    'country'=>$body['data']['country'],
                    'state'=>$body['data']['state'],
                    'municipality'=>$body['data']['municipality'],
                ],
                'address'=>[
                    'street'=>$body['address']['street'],
                    'cross_street1'=>$body['address']['cross_street1'],
                    'cross_street2'=>$body['address']['cross_street2'],
                    'house_number'=>$body['address']['house_number'],
                    'suburb'=>$body['address']['suburb'],
                    'postcode'=>$body['address']['postcode']
                ],
                'link'=>[
                    'self'=>$body['link']['self']
                ],
                'User'=>[
                    'data'=>[
                        'name'=>$body['User']['data']['name'],
                        'user'=>$body['User']['data']['user'],
                        'password'=>$body['User']['data']['password'],
                        'birthdate'=>$body['User']['data']['birthdate'],
                        'cellphone'=>$body['User']['data']['cellphone'],
                        'email'=>$body['User']['data']['email'],
                    ],
                    'link'=>[
                        'self'=>$body['User']['link']['self'],
                    ]
                ]
            ]);
            $this->assertDatabaseHas(
             'houses'
            ,[
                'id'=>$body['id'],
                'user_id'=>$body['id_user'],
                'address_id'=>$body['id'],
                'description'=>$body['data']['description'],
                'price_for_day'=>$body['data']['price_for_day'],
                'status'=>$body['data']['status'],
                'country'=>$body['data']['country'],
                'state'=>$body['data']['state'],
                'municipality'=>$body['data']['municipality'],
            ]);
            $this->assertDatabaseHas(
                'addresses'
                ,[
                    'id'=>$body['id'],
                    'street'=>$body['address']['street'],
                    'cross_street1'=>$body['address']['cross_street1'],
                    'cross_street2'=>$body['address']['cross_street2'],
                    'house_number'=>$body['address']['house_number'],
                    'suburb'=>$body['address']['suburb'],
                    'postcode'=>$body['address']['postcode']
            ]);


    }
     /**
     * UPDATE-1
     */
    public function test_user_can_update_a_product()
    {

        $user = factory(User::class)->create();
        $address=factory(Address::class)->create();
        $house= factory(House::class)->create([
            'user_id'=>$user['id'],
            'address_id'=>$address['id']
        ]);

        $houseData =[
            "data"=>[
              "description"=> "house",
              "price_for_day"=> "22",
              "status"=> "true",
              "country"=> "mexico",
              "state"=> "Quintana Roo",
              "municipality"=> "Cancun"
            ]
              ,
            "address"=> [
              "street"=> "29",
              "cross_street1"=> "31",
              "cross_street2"=> "33",
              "house_number"=> "23",
              "suburb"=> "centro",
              "postcode"=> "97000"
            ]
            ];
            $response = $this->actingAs($user)->json('PUT', '/api/houses/'.$house['id'].'?api_token='.$user['api_token'], $houseData);
            $response->assertStatus(200);

            $body = $response->decodeResponseJson();
            $response->assertJson([
                'id'=>$body['id'],
                'id_user'=>$body['id_user'],
                'data'=>[
                    'description'=>$houseData['data']['description'],
                    'price_for_day'=>$houseData['data']['price_for_day'],
                    'status'=>$houseData['data']['status'],
                    'country'=>$houseData['data']['country'],
                    'state'=>$houseData['data']['state'],
                    'municipality'=>$houseData['data']['municipality'],
                ],
                'address'=>[
                    'street'=>$houseData['address']['street'],
                    'cross_street1'=>$houseData['address']['cross_street1'],
                    'cross_street2'=>$houseData['address']['cross_street2'],
                    'house_number'=>$houseData['address']['house_number'],
                    'suburb'=>$houseData['address']['suburb'],
                    'postcode'=>$houseData['address']['postcode']
                ],
                'link'=>[
                    'self'=>$body['link']['self']
                ],
                'User'=>[
                    'data'=>[
                        'name'=>$body['User']['data']['name'],
                        'user'=>$body['User']['data']['user'],
                        'password'=>$body['User']['data']['password'],
                        'birthdate'=>$body['User']['data']['birthdate'],
                        'cellphone'=>$body['User']['data']['cellphone'],
                        'email'=>$body['User']['data']['email'],
                    ],
                    'link'=>[
                        'self'=>$body['User']['link']['self'],
                    ]
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
