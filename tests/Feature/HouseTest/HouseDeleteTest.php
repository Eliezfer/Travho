<?php

namespace Tests\Feature\HouseTest;
use App\House;
use App\Address;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HouseDeleteTest extends TestCase
{
    use RefreshDatabase;

     /**
     * DELETE-1
     */
    public function test_user_can_delete_a_house()
    {

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
     * DELETE-2
     */
    public function test_user_can_delete_a_house_is_not_authenticate(){
        $user = factory(User::class)->create();
        $address=factory(Address::class)->create();
        $house= factory(House::class)->create([
            'user_id'=>$user['id'],
            'address_id'=>$address['id']
        ]);
        $response= $this->actingAs($user)->DELETE('/api/v1/houses/'.$house['id']);
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
     * DELETE-3
     */
    public function test_user_can_delete_a_house_is_not_Authorized()
    {

        $user2 = factory(User::class)->create();
        $user = factory(User::class)->create();
        $address=factory(Address::class)->create();
        $house= factory(House::class)->create([
            'user_id'=>$user['id'],
            'address_id'=>$address['id']
        ]);
        //cuando
        $response= $this->actingAs($user)->DELETE('/api/v1/houses/'.$house['id'].'?api_token='.$user2['api_token']);

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
     * DELETE-4
     */
    public function test_user_can_delete_a_house_id_not_exist()
    {
        $user = factory(User::class)->create();
        $address=factory(Address::class)->create();
        $house= factory(House::class)->create([
            'user_id'=>$user['id'],
            'address_id'=>$address['id']
        ]);
        //generamos una id inexistente
       $id= $house['id']+1;
        $response= $this->actingAs($user)->DELETE('/api/v1/houses/'.$id.'?api_token='.$user['api_token']);

        $response->assertStatus(404);

        $response->assertJson([
            "errors"=> [
                "code"=> "ERROR-2",
                "title"=> "NOT FOUND",
                "message"=> "No se encontro el recurso"
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
