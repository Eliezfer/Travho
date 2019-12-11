<?php

namespace Tests\Feature;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illumintae\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class UserTest extends TestCase
{
    
    public function test_client_can_create_user()
    {
        // GIVEN 
        // El cliente tiene una rapresentación de un Usuario que quiere agregar a la aplicación
        // Request Body
        $UserData = [
            'data' => [
                'name' => 'Alejandro',
                'user' => 'AGC',
                'password' => '12345',
                'cellphone' => '5435678',
                'email' => 'agcfinal1.0@gmail.com',
                'birthdate' => '2/12/97',
                'api_token' => '',
            ]
        ];

        // When 
        // Se llama al EndPoint para crear un usuario
        $response = $this->json('POST', '/api/v1/users', $UserData);
 
        // Se verifica el código de Status
        $response->assertStatus(201);

        // $response->assertJson([
        //     'id' => '1',
        //     'data' => [
        //         'name' => "Alejandro",
        //         'user' => 'AGC',
        //         'date' => '2/12/97',
        //         'cellphone' => '5435678',
        //         'email' => 'agcfinal1.0@gmail.com',
                
        //     ],
        //     'link' => [
        //         "self" => env("APP_URL")."/api/v1/users/1",
        //     ]

        // ]);

  


    }
}
