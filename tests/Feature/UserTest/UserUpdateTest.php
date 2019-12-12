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

class UserUpdateTest extends TestCase
{
    use RefreshDatabase;

        /**
         * UPDATE-1
         * MODIFICAR ATRIBUTOS DE USUARIO
         */
        
        public function test_client_update_user()
        {
            //Given 
            // Existe una representación en la base de datos 
            $user = factory(User::class)->create([
                'email' => 'agcfinal1.0@gmail.com',
                'user' => 'AGC',
                'birthdate' => '1997-12-03',
                'cellphone' => '5435678',
                'name' => 'Alejandro',
                'password' => '12345',
                'api_token' => Str::random(80),
            ]);

            // Se tiene una representación del usuario
            $userDataUpdate =[
                "data" => [
                    "type" => "user",
                    "attributes" => [
                        "name" => "AlejandroEdited",
                        "user" => "AGCedit",
                        "password" => "123",
                        "cellphone" => "5435678",
                        "email" => "agcfinal1.0@gmail.com",
                        "birthdate" => '1997-12-02',
                    ]
                ]
            ];  

            // WHEN
            // Se envía un request con la información necesaria para Editar un usuario
            // No se envía Token
            $response = $this->json('PUT',"api/v1/users/".$user->id."?api_token=".$user['api_token'], $userDataUpdate);

            $response->assertJson([
                'id' => $user->id,
                'data' => [
                    'name' => 'AlejandroEdited',
                    'user' => 'AGCedit',
                    'birthdate' => '1997-12-02',
                    'cellphone' => '5435678',
                    'email' => 'agcfinal1.0@gmail.com',
                ],
                'link' => [
                    "self" => env("APP_URL").':8000/api/v1/users/'.$user->id,
                ]
    
            ]);

            // Se tiene una representación del usuario
            $userDataUpdate =[
            "name" => "AlejandroEdited",
            "user" => "AGCedit",
            "password" => "123",
            "cellphone" => "5435678",
            "email" => "agcfinal1.0@gmail.com",
            "birthdate" => '1997-12-02',
            ];  

            $this->assertDatabaseHas('users',$userDataUpdate);

            // Then 
            // Se asegura el status HTTP recibido
            $response->assertStatus(200);

        }


        /**
         * UPDATE-2
         * AUTENTICADO
         */
        public function test_client_no_authenticated()
        {
            //Given 
        
            // Existe una representación en la base de datos 
            $user = factory(User::class)->create([
                'email' => 'agcfinal1.0@gmail.com',
                'user' => 'AGC',
                'birthdate' => '1997-12-02',
                'cellphone' => '5435678',
                'name' => 'Alejandro',
                'password' => '12345',
                'api_token' => Str::random(80),
            ]);

            // Se tiene una representación del usuario
            $userDataUpdate =[
                "data" => [
                    "type" => "user",
                    "attribute" => [
                        "name" => "AlejandroEdited",
                        "user" => "AGCedit",
                        "password" => "123",
                        "cellphone" => "5435678",
                        "email" => "agclog@gmail.com",
                        "birthdate" => '1997-12-02',
                    ]
                ]
            ];  

            // WHEN
            // Se envía un request con la información necesaria para Editar un usuario
            // No se envía Token
            $response = $this->json('PUT',"api/v1/users/".$user->id);

            $response->assertJson([
                'errors' => [
                    'code' => 'ERROR-4',
                    'title' => 'UNAUTHORIZED',
                    'message' => 'Consulte Autenticación de acceso básica y Autenticación de acceso resumido',
                ]
            ]);

            // Then 
            // Se asegura el status HTTP recibido
            $response->assertStatus(401);
        }
            
        /**
         * UPDATE-3
        * AUTORIZADO
        */
        public function test_client_no_authorization_update()
        {            
            //Given 
            // Existe una representación en la base de datos 
            $user = factory(User::class)->create([
                'email' => 'agcfinal1.0@gmail.com',
                'user' => 'AGC',
                'birthdate' => '1997-12-02',
                'cellphone' => '5435678',
                'name' => 'Alejandro',
                'password' => '12345',
                'api_token' => Str::random(80),
                'status' => true,
            ]);

            // Existe una representación en la base de datos 
            $userHack = factory(User::class)->create([
                'email' => 'agcfinal2.0@gmail.com',
                'user' => 'AGC2',
                'birthdate' => '1997-12-02',
                'cellphone' => '5435678',
                'name' => 'Alejandro2',
                'password' => '123',
                'api_token' => Str::random(80),
                'status' => true,
            ]);

            // Se tiene una representación del usuario
            $userDataUpdate =[
                "data" => [
                    "type" => "user",
                    "attributes" => [
                        "name" => "AlejandroEdited",
                        "user" => "AGCedit",
                        "password" => "123",
                        "cellphone" => "5435678",
                        "email" => "agclog@gmail.com",
                        "birthdate" => '1997-12-02',
                    ]
                ]
            ];  

            // WHEN
            // Se envía un request con la información necesaria para Editar un usuario
            // No se envía Token
            $response = $this->json('PUT',"api/v1/users/".$user->id."?api_token=".$userHack['api_token'], $userDataUpdate);

            $response->assertJson([
                'errors' => [
                    'code' => 'ERROR-3',
                    'title' => 'FORBIDDEN',
                    'message' => 'This action is unauthorized.',
                ]
            ]);

            // Then 
            // Se asegura el status HTTP recibido
            $response->assertStatus(403);

        }

        /**
        * UPDATE-4
        * NOT FOUND
        */
        public function test_not_found_user_update()
        {
            //Given 
            // Existe una representación en la base de datos 
            $user = factory(User::class)->create();

                // Se tiene una representación del usuario
                $userDataUpdate =[
                    "data" => [
                        "type" => "user",
                        "attributes" => [
                            "name" => "AlejandroEdited",
                            "user" => "AGCedit",
                            "password" => "123",
                            "cellphone" => "5435678",
                            "email" => "agclog@gmail.com",
                            "birthdate" => '1997-12-02',
                        ]
                    ]
                ];  

                            // WHEN
                // Se envía un request con la información necesaria para Editar un usuario
                // No se envía Token
                $response = $this->json('PUT',"api/v1/users/".($user->id+1)."?api_token=".$user['api_token'], $userDataUpdate);

                $response->assertJson([
                    'errors' => [
                        'code' => 'ERROR-2',
                        'title' => 'NOT FOUND',
                        'message' => 'No se encontro el recurso',
                    ]
                ]);

                // Then 
                // Se asegura el status HTTP recibido
                $response->assertStatus(404);

        }

        /**
         * UPDATE-5
         * FORM - REQUEST 
         */
        public function test_dont_send_email()
        {
            //Given 
            // Existe una representación en la base de datos 
            $user = factory(User::class)->create();
            // Se tiene una representación del usuario
            $userDataUpdate =[
                "data" => [
                    "type" => "user",
                    "attributes" => [
                        "name" => "",
                        "user" => "AGCedit",
                        "password" => "123",
                        "cellphone" => "5435678",
                        "email" => "agcfinal1.0@gmail.com",
                        "birthdate" => '1997-12-02',
                    ]
                ]
            ];  

            // WHEN
            // Se envía un request con la información necesaria para Editar un usuario
            // No se envía Token
            $response = $this->json('PUT',"api/v1/users/".$user->id."?api_token=".$user['api_token'], $userDataUpdate);

            // THEN 
            // Retornar código 422 
            
            $response->assertStatus(422);


            // Asegurar que el usuario es correcto, verificando su 
            // email y password            
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

        }


        public function test_dont_send_user_update()
        {
            //Given 
            // Existe una representación en la base de datos 
            $user = factory(User::class)->create();
            // Se tiene una representación del usuario
            $userDataUpdate =[
                "data" => [
                    "type" => "user",
                    "attributes" => [
                        "name" => "Alejandro",
                        "user" => "",
                        "password" => "123",
                        "cellphone" => "5435678",
                        "email" => "agcfinal1.0@gmail.com",
                        "birthdate" => '1997-12-02',
                    ]
                ]
            ];  

            // WHEN
            // Se envía un request con la información necesaria para Editar un usuario
            // No se envía Token
            $response = $this->json('PUT',"api/v1/users/".$user->id."?api_token=".$user['api_token'], $userDataUpdate);

            // THEN 
            // Retornar código 422 
            
            $response->assertStatus(422);


            // Asegurar que el usuario es correcto, verificando su 
            // email y password            
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

        }


        public function test_dont_send_password_update()
        {
            //Given 
            // Existe una representación en la base de datos 
            $user = factory(User::class)->create();
            // Se tiene una representación del usuario
            $userDataUpdate =[
                "data" => [
                    "type" => "user",
                    "attributes" => [
                        "name" => "Alejandro",
                        "user" => "AGC",
                        "password" => "",
                        "cellphone" => "5435678",
                        "email" => "agcfinal1.0@gmail.com",
                        "birthdate" => '1997-12-02',
                    ]
                ]
            ];  

            // WHEN
            // Se envía un request con la información necesaria para Editar un usuario
            // No se envía Token
            $response = $this->json('PUT',"api/v1/users/".$user->id."?api_token=".$user['api_token'], $userDataUpdate);

            // THEN 
            // Retornar código 422 
            
            $response->assertStatus(422);


            // Asegurar que el usuario es correcto, verificando su 
            // email y password            
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

        }

        
}