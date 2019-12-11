<?php

namespace Tests\Feature\UserTest;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class UserTest extends TestCase
{
    // Colocar RefreshDatabase
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_client_can_create_user_UserTest()
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
                'api_token' => ''
            ]
        ];

        // When 
        // Se llama al EndPoint para crear un usuario
        $response = $this->json('POST', 'v1/api/users', $UserData);

        // Se verifica el código de Status
        $response->assertStatus(201);

        $response->assertJson([
            'id' => '1',
            'data' => [
                'nombre' => "Alejandro",
                'usuario' => 'AGC',
                'date' => '2/12/97',
                'teléfono' => '5435678',
                'correo' => 'agcfinal1.0@gmail.com',
                
            ],
            'link' => [
                "self" => env("APP_URL")."/api/v1/users/1",
            ]

        ]);

   



    }
}
