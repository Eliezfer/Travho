<?php

namespace Tests\Feature;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illumintae\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     *  CREATE-1
     */
    public function test_client_can_create_user()
    {
        //Given 
        // El Cliente Tiene tiene una representación de un usuario que quiere agregar a la aplicación
        // Request Body
        $userData = [
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
        // Se llama al Endpoint para crear un producto    
        $response = $this->json('POST', '/api/v1/users', $userData);
        // Then 
        // Se asegura el status HTTP recibido
        $response->assertStatus(201);

          // Assert the response has the correct structure and the correct values
          $response->assertJson([
                'id' => 1,
                'data' => [
                    'name' => 'Alejandro',
                    'user' => 'AGC',
                    'birthdate' => '2/12/97',
                    'cellphone' => '5435678',
                    'email' =>'agcfinal1.0@gmail.com',
                ],
                'link' => [
                    "self" => env("APP_URL").':8000/api/v1/users/1',
                ]

            ]);
        // Se asegura que el usuario fue creado 
        // con la información correcta 
        $response->assertJsonFragment([
            'name' => 'Alejandro',
            'user' => 'AGC',
            'birthdate' => '2/12/97',
            'cellphone' => '5435678',
            'email' =>'agcfinal1.0@gmail.com',
        ]);
        // Se decodifica el body
        $body = $response->decodeResponseJson();

        // Se asegura que el usuario está en la base de datos
        $this->assertDatabaseHas(
            'users',
            [
                'id' => '1',
                'name' => 'Alejandro',
                'user' => 'AGC',
                'birthdate' => '2/12/97',
                'cellphone' => '5435678',
                'email' => 'agcfinal1.0@gmail.com',
                'password' => '12345',
            ]
        );

    }

    /**
     * CREATE-2
     *      NO se envía el Nombre del usuario
     */
    public function test_client_dont_send_name_in_create()
    {
        //Given 
        // El cliente no envía el atributo "name" en el cuerpo de la solicitud     
       // Request Body
        $userData = [
            'data' => [
                'name' => '',
                'user' => 'AGC',
                'password' => '12345',
                'cellphone' => '5435678',
                'email' => 'agcfinal1.0@gmail.com',
                'birthdate' => '2/12/97',
                'api_token' => ''
            ]
        ];

        // When
        // Se llama al Endpoint de crear
         $response = $this->json('POST', '/api/v1/users', $userData);

         
        // Then 
        // Se verifica el contenido y estructura del Json de Respuesta
        $response->assertJson([
            "errors" => [[
                "code" => "Error-1",
                "title" => "Unprocessable Entity",
            ]]
        ]);

        
        // Se asegura el código HTTP 
        $response->assertStatus(422);
    }

    /**
     * CREATE-3
     *  No Username (NickName)
     */
    public function test_client_dont_send_nickname_in_create()
    {
        //Given 
        // El cliente no envía el atributo "user" en el cuerpo de la solicitud     
       // Request Body
        $userData = [
            'data' => [
                'name' => 'Alejandro',
                'user' => '',
                'password' => '12345',
                'cellphone' => '5435678',
                'email' => 'agcfinal1.0@gmail.com',
                'birthdate' => '2/12/97',
                'api_token' => ''
            ]
        ];

        // When
        // Se llama al Endpoint de crear
        $response = $this->json('POST', '/api/v1/users', $userData);

        
        // Then 
        // Se verifica el contenido y estructura del Json de Respuesta
        $response->assertJson([
            "errors" => [[
                "code" => "Error-1",
                "title" => "Unprocessable Entity",
            ]]
        ]);

        
        // Se asegura el código HTTP 
        $response->assertStatus(422);
    }
    /**
     * CREATE-4
     *  No Password
     */
    public function test_client_dont_send_password_in_create()
    {
        //Given 
        // El cliente no envía el atributo "password" en el cuerpo de la solicitud     
       // Request Body

       // Password Puede ser como el usuario  DESEE
        $userData = [
            'data' => [
                'name' => 'Alejandro',
                'user' => 'AGC',
                'password' => '', // **
                'cellphone' => '5435678',
                'email' => 'agcfinal1.0@gmail.com',
                'birthdate' => '2/12/97',
                'api_token' => ''
            ]
        ];
        // When
        // Se llama al Endpoint de crear
        $response = $this->json('POST', '/api/v1/users', $userData);
    
        // Then 
        // Se verifica el contenido y estructura del Json de Respuesta
        $response->assertJson([
            "errors" => [[
                "code" => "Error-1",
                "title" => "Unprocessable Entity",
            ]]
        ]);

        
        // Se asegura el código HTTP 
        $response->assertStatus(422);
        

    }
    /**
     * CREATE-5
     * No número de celular
     */
    public function test_client_dont_send_cellphone_in_create()
    {
        //Given 
        // El cliente no envía el atributo "cellphone" en el cuerpo de la solicitud     
        // Request Body

        
        $userData = [
            'data' => [
                'name' => 'Alejandro',
                'user' => 'AGC',
                'password' => '12345', 
                'cellphone' => '', // **
                'email' => 'agcfinal1.0@gmail.com',
                'birthdate' => '2/12/97',
                'api_token' => ''
            ]
        ];
        // When
        // Se llama al Endpoint de crear
        $response = $this->json('POST', '/api/v1/users', $userData);

        // Then 
        // Se verifica el contenido y estructura del Json de Respuesta
        $response->assertJson([
            "errors" => [[
                "code" => "Error-1",
                "title" => "Unprocessable Entity",
            ]]
        ]);

        
        // Se asegura el código HTTP 
        $response->assertStatus(422);
        
    }

    /**
     * CREATE-6
     * Número telefónico no es un número
     */
    public function test_client_dont_send_cellphone_number_in_create()
    {
            //Given 
            //El cliente envía el atributo cellphone de manera que NO es un número     
        // Request Body

        // Teléfono debe ser numérico
        $userData = [
            'data' => [
                'name' => 'Alejandro',
                'user' => 'AGC',
                'password' => '12345', 
                'cellphone' => 'NONUMERO', // **
                'email' => 'agcfinal1.0@gmail.com',
                'birthdate' => '2/12/97',
                'api_token' => ''
            ]
        ];
        // When
        // Se llama al Endpoint de crear
        $response = $this->json('POST', '/api/v1/users', $userData);

        // Then 
        // Se verifica el contenido y estructura del Json de Respuesta
        $response->assertJson([
            "errors" => [[
                "code" => "Error-1",
                "title" => "Unprocessable Entity",
            ]]
        ]);

        
        // Se asegura el código HTTP 
        $response->assertStatus(422);        
    }

    /**
     * CREATE-7
     * Email no enviado
     */
    public function test_client_dont_send_email_in_create()
    {
            //Given 
            // El cliente no envía el atributo "email" en el cuerpo de la solicitud     
        // Request Body

        // No se envía el correo electrónico
        $userData = [
            'data' => [
                'name' => 'Alejandro',
                'user' => 'AGC',
                'password' => '12345', 
                'cellphone' => '12345', 
                'email' => '', // **
                'birthdate' => '2/12/97',
                'api_token' => ''
            ]
        ];
        // When
        // Se llama al Endpoint de crear
        $response = $this->json('POST', '/api/v1/users', $userData);

        // Then 
        // Se verifica el contenido y estructura del Json de Respuesta
        $response->assertJson([
            "errors" => [[
                "code" => "Error-1",
                "title" => "Unprocessable Entity",
            ]]
        ]);

        
        // Se asegura el código HTTP 
        $response->assertStatus(422);    
    }

    /**
     * CREATE-8
     * Email no válido por formato
     */
    public function test_client_send_invalid_email_in_create()
    {
            //Given 
            // El cliente no envía un atributo "email" válido en el cuerpo de la solicitud     
        // Request Body

        // Email con el formato erróneo
        $userData = [
            'data' => [
                'name' => 'Alejandro',
                'user' => 'AGC',
                'password' => '12345', 
                'cellphone' => '12345', 
                'email' => 'NotValid@', // **
                'birthdate' => '2/12/97',
                'api_token' => ''
            ]
        ];
        // When
        // Se llama al Endpoint de crear
        $response = $this->json('POST', '/api/v1/users', $userData);

        // Then 
        // Se verifica el contenido y estructura del Json de Respuesta
        $response->assertJson([
            "errors" => [[
                "code" => "Error-1",
                "title" => "Unprocessable Entity",
            ]]
        ]);

        
        // Se asegura el código HTTP 
        $response->assertStatus(422);   

    }

    /**
     * CREATE-9 
     */
    public function test_client_dont_send_birthdate_in_create()
    {
            //Given 
            // El cliente no envía el atributo "birthdate" en el cuerpo de la solicitud     
        // Request Body

        // No se envía cumpleaños en body
        $userData = [
            'data' => [
                'name' => 'Alejandro',
                'user' => 'AGC',
                'password' => '12345', 
                'cellphone' => '12345', 
                'email' => 'agcfinal1.0@gmail.com', 
                'birthdate' => '',
                'api_token' => ''
            ]
        ];
        // When
        // Se llama al Endpoint de crear
        $response = $this->json('POST', '/api/v1/users', $userData);

        // Then 
        // Se verifica el contenido y estructura del Json de Respuesta
        $response->assertJson([
            "errors" => [[
                "code" => "Error-1",
                "title" => "Unprocessable Entity",
            ]]
        ]);

        
        // Se asegura el código HTTP 
        $response->assertStatus(422);    
    }

    /**
     * CREATE-10 
     */
    public function test_client_send_invalid_birthdate_in_create()
    {
        //Given 
        // El cliente no envía el atributo "birthdate" en el cuerpo de la solicitud     
        // Request Body

        $userData = [
            'data' => [
                'name' => 'Alejandro',
                'user' => 'AGC',
                'password' => '12345', 
                'cellphone' => '12345', 
                'email' => 'agcfinal1.0@gmail.com', 
                'birthdate' => '1234', //***/
                'api_token' => ''
            ]
        ];
        // When
        // Se llama al Endpoint de crear
        $response = $this->json('POST', '/api/v1/users', $userData);

        // Then 
        // Se verifica el contenido y estructura del Json de Respuesta
        $response->assertJson([
            "errors" => [[
                "code" => "Error-1",
                "title" => "Unprocessable Entity",
            ]]
        ]);

        
        // Se asegura el código HTTP 
        $response->assertStatus(422);    
    
    }

    /**
     * LOGIN-1
     */
    public function test_client_can_login()
    {
        //Given 
        // El cliente una representación del usuario para loguearse en la API
        // Request Body

        $userData = [
            'data' => [
                'email' => 'agcfinal1.0@gmail.com', 
                'password' => '12345', 
            ]
        ];


        // Existe una representación en la base de datos 
        $user = factory(User::class)->create([
            'email' => 'agcfinal1.0@gmail.com',
            'user' => 'AGC',
            'name' => 'Alejandro',
            'password' => '12345',
            'api_token' => Str::random(80),
        ]);

        // WHEN
        // Se envía un request con la información necesaria para loguear un usuario
        $response = $this->json('POST',"api/v1/users/login", $userData);

        // THEN 
        // Retornar código 200
        
        $response->assertStatus(200);

        $body = $response->decodeResponseJson();

        // Asegurar que el usuario es correcto, verificando su 
        // email y password            
        $response->assertJson([
            'data' => [
                'name' => $user->name,
                'user' => $user->user,
                'email' => $user->email,
                'api_token' => $user->api_token,
            ]
        ]);
        
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
                'email' => '', 
                'password' => '12345', 
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
       * No email formato correcto
       */
    public function test_client_send_invalid_email_login()
    {
        //Given 
        // El cliente una representación del usuario para loguearse en la API
        // Request Body

        $userData = [
            'data' => [
                'email' => 'alejandro', 
                'password' => '12345', 
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
