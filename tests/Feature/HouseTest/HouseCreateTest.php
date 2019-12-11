<?php

namespace Tests\Feature\HouseTest;
use App\House;
use App\Address;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HouseCreateTest extends TestCase
{
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
        $response = $this->actingAs($user)->json('POST', '/api/v1/houses?api_token='.$user['api_token'], $houseData);
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
        ]);
        $response->assertJson([

                'id'=>$body['id'],
                'id_user'=>$body['id_user'],
                'data'=>[
                    'description'=>$body['data']['description'],
                    'price_for_day'=>$body['data']['price_for_day'],
                    'status'=>$body['data']['status'],
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
     * CREATE-2
     */
    public function test_user_can_create_a_house_is_not_authenticate(){
        //dado

     $houseData =[
            "data"=>[
              "description"=> "new house",
              "price_for_day"=> "22",
              "status"=> "true",
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
        $response = $this->json('POST','/api/v1/houses?api_token=1', $houseData);
            //entonces
        $body = $response->decodeResponseJson();
        $response->assertStatus(401);
        //verificar la estructura devuelta
        $response->assertJson([
                "message"=> [
                    "code"=> "ERROR-4",
                    "title"=> "UNAUTHORIZED",
                    "message"=> "Consulte Autenticación de acceso básica y Autenticación de acceso resumido"
                ]

            ]);
    }
    /**
     * CREATE-3
     */
    public function test_user_can_create_a_house_price_for_day_is_not_a_number(){
        $user = factory(User::class)->create();
        $houseData =[
               "data"=>[
                 "description"=> "new house",
                 "price_for_day"=> "d",
                 "status"=> "true",
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
           $response = $this->actingAs($user)->json('POST', '/api/v1/houses?api_token='.$user['api_token'], $houseData);
               //entonces
           $body = $response->decodeResponseJson();
           $response->assertStatus(422);
           $response->assertJson([
            "errors"=> [
                [
                    "code"=> "ERROR-1",
                    "title"=> "Uprocessable Entity"
                ]
            ]

        ]);

    }
    /**
     * CREATE-4
     */
    public function test_user_can_create_a_house_price_for_day_is_less_than_0(){
        $user = factory(User::class)->create();
        $houseData =[
               "data"=>[
                 "description"=> "new house",
                 "price_for_day"=> "-3",
                 "status"=> "true",
                 "state"=> "belga",
                 "municipality"=> "Merida"
               ]
                 ,
               "address"=> [
                 "street"=> "29",
                 "cross_street1"=> "31",
                 "cross_street2"=> "33",
                 "house_number"=> "23",
                 "suburb"=> "centro",
                 "postcode"=> "w"
               ]
               ];
                //cuando
           $response = $this->actingAs($user)->json('POST', '/api/v1/houses?api_token='.$user['api_token'], $houseData);
               //entonces
           $body = $response->decodeResponseJson();
           $response->assertStatus(422);
           $response->assertJson([
            "errors"=> [
                [
                    "code"=> "ERROR-1",
                    "title"=> "Uprocessable Entity"
                ]
            ]

        ]);

    }
    /**
     * CREATE-5
     */
    public function test_user_can_create_a_house_state_is_not_a_of_mexico(){
        $user = factory(User::class)->create();
        $houseData =[
               "data"=>[
                 "description"=> "new house",
                 "price_for_day"=> "22",
                 "status"=> "true",
                 "state"=> "belga",
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
           $response = $this->actingAs($user)->json('POST', '/api/v1/houses?api_token='.$user['api_token'], $houseData);
               //entonces
           $body = $response->decodeResponseJson();
           $response->assertStatus(422);
           $response->assertJson([
            "errors"=> [
                [
                    "code"=> "ERROR-1",
                    "title"=> "Uprocessable Entity"
                ]
            ]

        ]);

    }
        /**
     * CREATE-6
     */
    public function test_user_can_create_a_house_postcode_is_not_a_number(){
        $user = factory(User::class)->create();
        $houseData =[
               "data"=>[
                 "description"=> "new house",
                 "price_for_day"=> "22",
                 "status"=> "true",
                 "state"=> "belga",
                 "municipality"=> "Merida"
               ]
                 ,
               "address"=> [
                 "street"=> "29",
                 "cross_street1"=> "31",
                 "cross_street2"=> "33",
                 "house_number"=> "23",
                 "suburb"=> "centro",
                 "postcode"=> "w"
               ]
               ];
                //cuando
           $response = $this->actingAs($user)->json('POST', '/api/v1/houses?api_token='.$user['api_token'], $houseData);
               //entonces
           $body = $response->decodeResponseJson();
           $response->assertStatus(422);
           $response->assertJson([
            "errors"=> [
                [
                    "code"=> "ERROR-1",
                    "title"=> "Uprocessable Entity"
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
