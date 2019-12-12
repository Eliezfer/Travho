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


class UserShowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * SHOW-1
     */

     public function test_client_can_view_a_user()
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

         // WHEN
        // Se envía un request con la información necesaria para mostrar un usuario
        $response = $this->json('GET',"api/v1/users/".$user->id."?api_token=".$user['api_token']);

        $response->assertJson([
            'id' => 1,
            'data' => [
                'name' => 'Alejandro',
                'user' => 'AGC',
                'birthdate' => $user->birthdate,
                'cellphone' => $user->cellphone,
                'email' =>'agcfinal1.0@gmail.com',
            ],
            'link' => [
                "self" => env("APP_URL").':8000/api/v1/users/1',
            ]

        ]);

        // Then 
        // Se asegura el status HTTP recibido
        $response->assertStatus(200);

     }

     /**
      * SHOW-2 
      * NO AUTENTICADO
      */
     public function test_client_isnt_authenticated()
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

         // WHEN
        // Se envía un request con la información necesaria para mostrar un usuario
        $response = $this->json('GET',"api/v1/users/".$user->id);

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
       * SHOW-3 
       * STATUS FALSE [DADO DE BAJA]
       */
     public function test_client_cant_see_user_deleted()
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
            'status' => false, // Borrado
        ]);

         // WHEN
        // Se envía un request con la información necesaria para mostrar un usuario
        $response = $this->json('GET',"api/v1/users/".$user->id."?api_token=".$user['api_token']);

        // No puede ser mostrado por estar dado de baja
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
        * SHOW-4 
        * NO ENCONTRADO
        */
        public function test_client_try_see_user_no_DB()
        {
            //Given 
            // Existe una representación en la base de datos 
            $user = factory(User::class)->create();

            // WHEN
            // Se envía un request con la información necesaria para mostrar un usuario
            $response = $this->json('GET',"api/v1/users/".($user->id+1)."?api_token=".$user['api_token']);

            // No puede ser mostrado por estar dado de baja
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


}