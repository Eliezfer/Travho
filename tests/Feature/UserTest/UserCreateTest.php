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

class UserCreateTest extends TestCase
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
                "type" => 'user',
                "attributes" => [
                'name' => 'Alejandro',
                'user' => 'AGC',
                'password' => '12345',
                'cellphone' => '5435678',
                'email' => 'agcfinal1.0@gmail.com',
                'birthdate' => '2/12/97',
                ]
            ]
        ];

        // When
        // Se llama al Endpoint para crear un producto    
        $response = $this->json('POST', '/api/v1/users', $userData);
        // Then 
        // Se asegura el status HTTP recibido
        $response->assertStatus(201);

          // Assert the response has the correct structure and the correct values
          $body = $response->decodeResponseJson();
          $response->assertJson([
                'id' => $body['id'],
                'data' => [
                    'name' => 'Alejandro',
                    'user' => 'AGC',
                    'birthdate' => '2/12/97',
                    'cellphone' => '5435678',
                    'email' =>'agcfinal1.0@gmail.com',
                ],
                'link' => [
                    "self" => env("APP_URL").':8000/api/v1/users/'.$body['id'],
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
                'id' => $body['id'],
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
            "type" => 'user',
            "attributes" => [
                'name' => '',
                'user' => 'AGC',
                'password' => '12345',
                'cellphone' => '5435678',
                'email' => 'agcfinal1.0@gmail.com',
                'birthdate' => '2/12/97',
            ]
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
                    "message" => [
                        "data.attributes.name" => 
                        ["El data.attributes.name no es enviado en la solicitud"]
                    ]
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
                "type" => 'user',
                "attributes" => [
                'name' => 'Alejandro',
                'user' => '',
                'password' => '12345',
                'cellphone' => '5435678',
                'email' => 'agcfinal1.0@gmail.com',
                'birthdate' => '2/12/97',
                ]
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
                    "message" => [
                        "data.attributes.user" => 
                        ["El data.attributes.user no es enviado en la solicitud"]
                    ]
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
                "type" => 'user',
                "attributes" => [
                'name' => 'Alejandro',
                'user' => 'AGC',
                'password' => '',
                'cellphone' => '5435678',
                'email' => 'agcfinal1.0@gmail.com',
                'birthdate' => '2/12/97',
                ]
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
                    "message" => [
                        "data.attributes.password" => 
                        ["El data.attributes.password no es enviado en la solicitud"]
                    ]
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
                "type" => 'user',
                "attributes" => [
                'name' => 'Alejandro',
                'user' => 'AGC',
                'password' => '12345',
                'cellphone' => '',
                'email' => 'agcfinal1.0@gmail.com',
                'birthdate' => '2/12/97',
                ]
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
                    "message" => [
                        "data.attributes.cellphone" => 
                        ["El data.attributes.cellphone no es enviado en la solicitud"]
                    ]
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
                "type" => 'user',
                "attributes" => [
                'name' => 'Alejandro',
                'user' => 'AGC',
                'password' => '12345',
                'cellphone' => 'NotNumber',
                'email' => 'agcfinal1.0@gmail.com',
                'birthdate' => '2/12/97',
                ]
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
                    "message" => [
                        "data.attributes.cellphone" => 
                        ["El data.attributes.cellphone debe ser un número"]
                    ]
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
                "type" => 'user',
                "attributes" => [
                'name' => 'Alejandro',
                'user' => 'AGC',
                'password' => '12345',
                'cellphone' => '5435678',
                'email' => '',
                'birthdate' => '2/12/97',
                ]
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
                    "message" => [
                        "data.attributes.email" => 
                        ["El data.attributes.email es requerido"]
                    ]
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
                "type" => 'user',
                "attributes" => [
                'name' => 'Alejandro',
                'user' => 'AGC',
                'password' => '12345',
                'cellphone' => '5435678',
                'email' => 'agcfinal1.0@',
                'birthdate' => '2/12/97',
                ]
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
                    "message" => [
                        "data.attributes.email" => 
                        ["El data.attributes.email debe tener formato test@correo.com "]
                    ]
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
                "type" => 'user',
                "attributes" => [
                'name' => 'Alejandro',
                'user' => 'AGC',
                'password' => '12345',
                'cellphone' => '5435678',
                'email' => 'agcfinal1.0@gmail.com',
                'birthdate' => '',
                ]
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
                    "message" => [
                        "data.attributes.birthdate" => 
                        ["La data.attributes.birthdate es requerida"]
                    ]
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
                "type" => 'user',
                "attributes" => [
                'name' => 'Alejandro',
                'user' => 'AGC',
                'password' => '12345',
                'cellphone' => '5435678',
                'email' => 'agcfinal1.0@gmail.com',
                'birthdate' => '2-12-97',
                ]
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
                    "message" => [
                        "data.attributes.birthdate" => 
                        ["La data.attributes.birthdate debe tener el formato [2/11/19]"]
                    ]
             ]]
        ]);

        
        // Se asegura el código HTTP 
        $response->assertStatus(422);    
    
    }
}