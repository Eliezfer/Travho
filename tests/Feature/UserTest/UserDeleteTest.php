<?php

namespace Tests\Feature\UserTest;
use App\User;
use App\House;
use App\BookingHouse;
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

class UserDeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * DELETE-1
     * ELIMINACIÓN DE BOOKING Y HOUSES
     */
        public function test_client_delete_user()
        {
            $user = factory(User::class)->create();
            $address=factory(BookingHouse::class)->create();
            $house= factory(House::class)->create([
                'user_id'=>$user['id'],
                'address_id'=>$address['id']
            ]); 

            // Se llama al Endpoint
            $response = $this->delete('/api/v1/users/'.($user->id)."?api_token=".$user->api_token);
            $response->assertStatus(204);
            $response->assertJson(null);
            
        }
    /**
     * DELETE-2
     * AUTENTICADO
     */
        public function test_client_no_authenticated_delete()
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
            // No se envía Token
             $response = $this->delete('/api/v1/users/'.$user->id);
             
             // Se retorna el json de error
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
      * DELETE-3
      * AUTORIZADO
      */
      public function test_client_no_authorization_delete()
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

            // Se llama al Endpoint
            $response = $this->delete('/api/v1/users/'.$user->id."?api_token=".$userHack->api_token);
            // Se retorna error-3 [403]
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
       * DELETE-4
       * NOT FOUND
       */
    public function test_client_delete_invalid_user(){
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

            // Se llama al Endpoint
            $response = $this->delete('/api/v1/users/'.($user->id+1)."?api_token=".$user->api_token);
            // Se verifica estructura y contenido del JSON
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