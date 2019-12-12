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


class UserLogoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * LOGOUT-1
     */
    public function test_client_can_logout()
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
        $response = $this->json('POST',"api/v1/users/logout?api_token=".$user['api_token'], $userData);


        // Asegurar que el usuario es correcto, verificando su 
        // email y password            
        $response->assertJson([
            "data" => [
                "type" => "user",
                "attributes" => [
                    "Session: " => "Logout",
                ]
            ]
        ]);

          // THEN 
        // Retornar código 200
        
        $response->assertStatus(200);
        
    }

    /**
     * LOGOUT-2
     *  No email
     */
    public function test_client_dont_send_email_logout()
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
                    'email' => '', 
                    'password' => '12345', 
                ]
            ]
        ];

        // WHEN
        // Se envía un request con la información necesaria para loguear un usuario
        $response = $this->json('POST',"api/v1/users/logout?api_token=".$user['api_token'], $userData);

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
       * LOGOUT-3
       * No email formato correcto
       */
    public function test_client_send_invalid_email_login()
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
                    'email' => 'agcfinal1.0@', 
                    'password' => '12345', 
                ]
            ]
        ];


        // WHEN
        // Se envía un request con la información necesaria para loguear un usuario
        $response = $this->json('POST',"api/v1/users/logout?api_token=".$user['api_token'], $userData);

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
      * LOGOUT-4
      * No password
      */
    public function test_client_dont_send_password()
    {
        //Given 
        // Hay una representación de un usuario en la DB
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
                    'password' => '', 
                ]
            ]
        ];

        // WHEN
        // Se envía un request con la información necesaria para loguear un usuario
        $response = $this->json('POST',"api/v1/users/logout?api_token=".$user['api_token'], $userData);

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
                        "data.attributes.password" => 
                        ["El data.attributes.password es requerido"],
                    ]
             ]]
        ]);

    }

    /**
     * LOGOUT-5
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
                'type' => 'user',
                "attributes" => [
                    'email' => 'agcfinal1.0@gmail.com', 
                    'password' => '12', 
                ]
            ]
        ];

                // WHEN
        // Se envía un request con la información necesaria para loguear un usuario
        $response = $this->json('POST',"api/v1/users/logout?api_token=".$user['api_token'], $userData);

        // THEN 
        // Retornar código 401
        
        $response->assertStatus(401);

        // Asegurar que el usuario es correcto, verificando su 
        // email y password            
        $response->assertJson([
            'errors' => [
                'code' => 'ERROR-4',
                'title' => 'UNAUTHORIZED',
                'message' => 'Consulte Autenticación de acceso básica y Autenticación de acceso resumido',
            ]
        ]);
    }

    /**
     * LOGOUT-6
     * 
     */
    public function test_client_send_email_no_database()
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
                'type' => 'user',
                "attributes" => [
                    'email' => 'agc@gmail.com', 
                    'password' => '12345', 
                ]
            ]
        ];
                        // WHEN
        // Se envía un request con la información necesaria para loguear un usuario
        $response = $this->json('POST',"api/v1/users/logout?api_token=".$user['api_token'], $userData);

        // THEN 
        // Retornar código 404
        
        $response->assertStatus(404);

        // Asegurar que el usuario es correcto, verificando su 
        // email y password            
        $response->assertJson([
            'errors' => [
                'code' => 'ERROR-2',
                'title' => 'NOT FOUND',
                'message' => 'No se encontro el recurso',
            ]
        ]);
    }

    /**
     * LOGOUT-7
     */
    public function test_client_try_see_user_delete()
    {
        //Given
        // Existe una representación en la base de datos 
        $user = factory(User::class)->create([
            'email' => 'agcfinal1.0@gmail.com',
            'user' => 'AGC',
            'name' => 'Alejandro',
            'password' => '12345',
            'status' => false,
            'api_token' => Str::random(80),
        ]);

               // El cliente tiene una representación del usuario para loguearse en la API
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
        $response = $this->json('POST',"api/v1/users/logout?api_token=".$user['api_token'], $userData);

        // THEN 
        // Retornar código 404
        
        $response->assertStatus(404);

        // Asegurar que el usuario es correcto, verificando su 
        // email y password            
        $response->assertJson([
            'errors' => [
                'code' => 'ERROR-2',
                'title' => 'NOT FOUND',
                'message' => 'No se encontro el recurso',
            ]
        ]);
    }

    /**
     * LOGOUT-8
     */
    public function test_client_no_authenticated()
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
        $response = $this->json('POST',"api/v1/users/logout", $userData);

        // Asegurar respuesta JSON
        $response->assertJson([
            "errors" => [
                "code" => "ERROR-4",
                "title" => "UNAUTHORIZED",
                "message" => "Consulte Autenticación de acceso básica y Autenticación de acceso resumido"
            ]
        ]);

          // THEN 
        // Retornar código 401
        
        $response->assertStatus(401);
    }
    /**
     * LOGOUT-9
     */
    public function test_client_no_authorized()
    {
                //Given 
        // Existen dos representación en la base de datos 
        $userClient = factory(User::class)->create([
            'email' => 'agcfinal1.0@gmail.com',
            'user' => 'AGC',
            'name' => 'Alejandro',
            'password' => '123',
            'api_token' => Str::random(80),
        ]);

        $userHack = factory(User::class)->create([
            'email' => 'agcfinal2.0@gmail.com',
            'user' => 'AGC2',
            'name' => 'Alejandro2',
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
        $response = $this->json('POST',"api/v1/users/logout?api_token=".$userHack['api_token'], $userData);


        // Se asegura la respuesta del Server          
        $response->assertJson([
            "errors" => [
                "code" => "ERROR-3",
                "title" => "FORBIDDEN",
                "message" => "This action is unauthorized."
            ]
        ]);

          // THEN 
        // Retornar código Forbidden
        
        $response->assertStatus(403);
    }
}