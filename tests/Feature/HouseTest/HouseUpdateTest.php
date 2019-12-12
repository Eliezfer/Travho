<?php

namespace Tests\Feature\HouseTest;
use App\House;
use App\Address;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HouseUpdateTest extends TestCase
{
    use RefreshDatabase;
    /**
     * UPDATE-1
     */
    public function test_user_can_update_a_house()
    {
        //exiten los modelos en la base de datos
        $user = factory(User::class)->create();
        $address=factory(Address::class)->create();
        $house= factory(House::class)->create([
            'user_id'=>$user['id'],
            'address_id'=>$address['id']
        ]);
        //dado
        $houseData =[
            "data"=>[
                "type"=>"house",
                "attributes"=>[
              "description"=> "new house",
              "price_for_day"=> "22",
              "status"=> "true",
              "state"=> "Yucatán",
              "municipality"=> "Merida",
                ],
              "address"=> [
                "street"=> "29",
                "cross_street1"=> "31",
                "cross_street2"=> "33",
                "house_number"=> "23",
                "suburb"=> "centro",
                "postcode"=> "97000"
              ]
            ]
        ];
            //cuando
            $response = $this->actingAs($user)->json('PUT', '/api/v1/houses/'.$house['id'].'?api_token='.$user['api_token'], $houseData);
            //entonces
            $response->assertStatus(200);

            $body = $response->decodeResponseJson();
            //verifica la estructura y el contenido de la respuesta
            $response->assertJson([
                'id'=>$body['id'],
                'id_user'=>$body['id_user'],
                'data'=>[
                    'description'=>$houseData['data']['attributes']['description'],
                    'price_for_day'=>$houseData['data']['attributes']['price_for_day'],
                    'status'=>$houseData['data']['attributes']['status'],
                    'state'=>$houseData['data']['attributes']['state'],
                    'municipality'=>$houseData['data']['attributes']['municipality'],
                ],
                'address'=>[
                    'street'=>$houseData['data']['address']['street'],
                    'cross_street1'=>$houseData['data']['address']['cross_street1'],
                    'cross_street2'=>$houseData['data']['address']['cross_street2'],
                    'house_number'=>$houseData['data']['address']['house_number'],
                    'suburb'=>$houseData['data']['address']['suburb'],
                    'postcode'=>$houseData['data']['address']['postcode']
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
            //verifico los nuevos datos en la base de datos
            $this->assertDatabaseHas(
                'houses'
               ,[
                   'id'=>$body['id'],
                   'user_id'=>$body['id_user'],
                   'address_id'=>$body['id'],
                   'description'=>$houseData['data']['attributes']['description'],
                   'price_for_day'=>$houseData['data']['attributes']['price_for_day'],
                   'status'=>$houseData['data']['attributes']['status'],
                   'state'=>$houseData['data']['attributes']['state'],
                   'municipality'=>$houseData['data']['attributes']['municipality'],
               ]);
               $this->assertDatabaseHas(
                   'addresses'
                   ,[
                       'id'=>$body['id'],
                       'street'=>$houseData['data']['address']['street'],
                       'cross_street1'=>$houseData['data']['address']['cross_street1'],
                       'cross_street2'=>$houseData['data']['address']['cross_street2'],
                       'house_number'=>$houseData['data']['address']['house_number'],
                       'suburb'=>$houseData['data']['address']['suburb'],
                       'postcode'=>$houseData['data']['address']['postcode']
               ]);


    }
    /**
     * UPDATE-2
     */
    public function test_user_can_update_a_house_is_not_authenticate()
    {
        //dado

      //exiten los modelos en la base de datos
      $user = factory(User::class)->create();
      $address=factory(Address::class)->create();
      $house= factory(House::class)->create([
          'user_id'=>$user['id'],
          'address_id'=>$address['id']
      ]);
      //dado
      $houseData =[
        "data"=>[
            "type"=>"house",
            "attributes"=>[
          "description"=> "new house",
          "price_for_day"=> "22",
          "status"=> "true",
          "state"=> "Yucatán",
          "municipality"=> "Merida",
            ],
          "address"=> [
            "street"=> "29",
            "cross_street1"=> "31",
            "cross_street2"=> "33",
            "house_number"=> "23",
            "suburb"=> "centro",
            "postcode"=> "97000"
          ]
        ]
    ];
          //cuando
          $response = $this->actingAs($user)->json('PUT', '/api/v1/houses/'.$house['id'], $houseData);
          //entonces
        $response->assertStatus(401);
        //verificar la estructura devuelta
        $response->assertJson([
                "errors"=> [
                    "code"=> "ERROR-4",
                    "title"=> "UNAUTHORIZED",
                    "message"=> "Consulte Autenticación de acceso básica y Autenticación de acceso resumido"
                ]

            ]);
    }
     /**
     * UPDATE-3
     */
    public function test_user_can_update_a_house_is_not_Authorized()
    {
        //dado

      //exiten los modelos en la base de datos
      $user2 = factory(User::class)->create();
      $user = factory(User::class)->create();
      $address=factory(Address::class)->create();
      $house= factory(House::class)->create([
          'user_id'=>$user['id'],
          'address_id'=>$address['id']
      ]);
      //dado
      $houseData =[
        "data"=>[
            "type"=>"house",
            "attributes"=>[
          "description"=> "new house",
          "price_for_day"=> "22",
          "status"=> "true",
          "state"=> "Yucatán",
          "municipality"=> "Merida",
            ],
          "address"=> [
            "street"=> "29",
            "cross_street1"=> "31",
            "cross_street2"=> "33",
            "house_number"=> "23",
            "suburb"=> "centro",
            "postcode"=> "97000"
          ]
        ]
    ];
          //cuando
        $response = $this->actingAs($user)->json('PUT', '/api/v1/houses/'.$house['id'].'?api_token='.$user2['api_token'], $houseData);
          //entonces
        $response->assertStatus(403);
        //verificar la estructura devuelta
        $response->assertJson([
                "errors"=> [
                    "code"=> "ERROR-3",
                    "title"=> "FORBIDDEN",
                    "message"=> "This action is unauthorized."
                ]
            ]);
    }
     /**
     * UPDATE-4
     */
    public function test_user_can_update_a_house_price_for_day_is_not_a_number()
    {
        $user = factory(User::class)->create();
        $address=factory(Address::class)->create();
        $house= factory(House::class)->create([
            'user_id'=>$user['id'],
            'address_id'=>$address['id']
        ]);
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
            $response = $this->actingAs($user)->json('PUT', '/api/v1/houses/'.$house['id'].'?api_token='.$user['api_token'], $houseData);
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
    public function test_user_can_update_a_house_price_for_day_is_less_than_0()
    {
        $user = factory(User::class)->create();
        $address=factory(Address::class)->create();
        $house= factory(House::class)->create([
            'user_id'=>$user['id'],
            'address_id'=>$address['id']
        ]);
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
            $response = $this->actingAs($user)->json('PUT', '/api/v1/houses/'.$house['id'].'?api_token='.$user['api_token'], $houseData);
                //entonces
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
    public function test_user_can_update_a_house_state_is_not_a_of_mexico()
    {
        $user = factory(User::class)->create();
        $address=factory(Address::class)->create();
        $house= factory(House::class)->create([
            'user_id'=>$user['id'],
            'address_id'=>$address['id']
        ]);
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
            $response = $this->actingAs($user)->json('PUT', '/api/v1/houses/'.$house['id'].'?api_token='.$user['api_token'], $houseData);
                //entonces
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
     * CREATE-7
     */
    public function test_user_can_update_a_house_postcode_is_not_a_number()
    {
        $user = factory(User::class)->create();
        $address=factory(Address::class)->create();
        $house= factory(House::class)->create([
            'user_id'=>$user['id'],
            'address_id'=>$address['id']
        ]); $houseData =[
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
            $response = $this->actingAs($user)->json('PUT', '/api/v1/houses/'.$house['id'].'?api_token='.$user['api_token'], $houseData);
                //entonces
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
