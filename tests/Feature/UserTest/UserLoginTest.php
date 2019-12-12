<?php

namespace Tests\Feature\UserTest;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;


class UserLoginTest extends TestCase
{
    use RefreshDatabase;

   /**
     * LOGIN-1
     */
    public function test_client_can_login()
    {
        //Given 
        // Existe una representación en la base de datos 
        $user = factory(User::class)->create([
            'email' => 'agcfinal1.0@gmail.com',
            'user' => 'AGC',
            'name' => 'Alejandro',
            'password' => '12345',
            'api_token' => Str::random(80),
        ]);
        // El cliente una representación del usuario para loguearse en la API
        // Request Body

        $userData = [
            'data' => [
                'type' => 'user',
                "attributes" => [
                    'email' => 'agcfinal1.0@gmail.com', 
                    'password' => '12345', 
                ]
            ]
        ];

        // WHEN
        // Se envía un request con la información necesaria para loguear un usuario
        $response = $this->json('POST',"api/v1/users/login", $userData);


        // Asegurar que el usuario es correcto, verificando su 
        // email y password            
        $response->assertJson([
            'data' => [
                'name' => $user->name,
                'user' => $user->user,
                'email' => $user->email,
                'api_token' => $user->api_token,
            ],
            'link' => [
                "self" => env('app_url').':8000/api/v1/users/'.$user->id,
            ]
        ]);

          // THEN 
        // Retornar código 200
        
        $response->assertStatus(200);
        
    }

    /**
     * LOGIN-2
     *  No email
     */
    public function test_client_dont_send_email_login()
    {
        //Given 
        // El cliente una representación del usuario para loguearse en la API
        // Request Body

        $userData = [
            'data' => [
                'type' => 'user',
                "attributes" => [
                    'email' => '', 
                    'password' => '12345', 
                ]
            ]
        ];

        // WHEN
        // Se envía un request con la información necesaria para loguear un usuario
        $response = $this->json('POST',"api/v1/users/login", $userData);

        // THEN 
        // Retornar código 404 
        
        $response->assertStatus(422);

        $body = $response->decodeResponseJson();

        // Asegurar que el usuario es correcto, verificando su 
        // email y password            
        $response->assertJson([
            "errors" => [[
                    "code" => "Error-1",
                    "title" => "Unprocessable Entity",
                    "message" => [
                        "data.attributes.email" => 
                        ["El data.attributes.email es requerido"],
                    ]
             ]]
        ]);

    }
      /**
       * LOGIN-4
       * No email formato correcto
       */
    public function test_client_send_invalid_email_login()
    {
        //Given 
        // El cliente una representación del usuario para loguearse en la API
        // Request Body

        $userData = [
            'data' => [
                'type' => 'user',
                "attributes" => [
                    'email' => 'agcfinal1.0@', 
                    'password' => '12345', 
                ]
            ]
        ];


        // WHEN
        // Se envía un request con la información necesaria para loguear un usuario
        $response = $this->json('POST',"api/v1/users/login", $userData);

        // THEN 
        // Retornar código 404 
        
        $response->assertStatus(422);


        // Asegurar que el usuario es correcto, verificando su 
        // email y password            
        $response->assertJson([
            "errors" => [[
                    "code" => "Error-1",
                    "title" => "Unprocessable Entity",
                    "message" => [
                        "data.attributes.email" => 
                        ["El data.attributes.email el formato del email no es correcto"]
                    ]
             ]]
        ]);

    }

       
     /**
      * LOGIN-3
      * No password
      */
    public function test_client_dont_send_password()
    {
                //Given 
        // El cliente una representación del usuario para loguearse en la API
        // Request Body

        $userData = [
            'data' => [
                'email' => 'alejandro@gmail.com ', 
                'password' => '', 
            ]
        ];

        // WHEN
        // Se envía un request con la información necesaria para loguear un usuario
        $response = $this->json('POST',"api/v1/users/login", $userData);

        // THEN 
        // Retornar código 404 
        
        $response->assertStatus(422);

        $body = $response->decodeResponseJson();

        // Asegurar que el usuario es correcto, verificando su 
        // email y password            
        $response->assertJson([
            'errors' => [[
                'code' => 'Error-1',
                'title' => 'Unprocessable Entity',
                ]
            ]
        ]);
    }

    /**
     * LOGIN-4
     * Password Incorrect
     */
    public function test_client_send_wrong_password()
    {

        //Given 

        // Existe una representación en la base de datos 
        $user = factory(User::class)->create([
                'email' => 'agcfinal1.0@gmail.com',
                'user' => 'AGC',
                'name' => 'Alejandro',
                'password' => '12345',
                'api_token' => Str::random(80),
            ]);
        
        
        // El cliente tiene una representación del usuario para loguearse en la API
        // Request Body
            $userData = [
                'data' => [
                    'email' => 'agcfinal1.0@gmail.com', 
                    'password' => '12', 
                ]
            ];

                // WHEN
        // Se envía un request con la información necesaria para loguear un usuario
        $response = $this->json('POST',"api/v1/users/login", $userData);

        // THEN 
        // Retornar código 401
        
        $response->assertStatus(401);

        $body = $response->decodeResponseJson();

        // Asegurar que el usuario es correcto, verificando su 
        // email y password            
        $response->assertJson([
            'data' => [
                'Password' => 'Incorrect',
            ]
        ]);
        }


}