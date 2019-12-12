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


        }


        /**
         * UPDATE-2
         * AUTENTICADO
         */
        public function test_client_no_authenticated()
        {

        }
            
        /**
         * UPDATE-3
        * AUTORIZADO
        */
        public function test_client_no_authorization()
        {

        }

        /**
        * UPDATE-4
        * NOT FOUND
        */
        public function test_not_found_user()
        {

        }

        /**
         * UPDATE-5
         * FORM - REQUEST 
         */
        public function test_dont_send()
        {

        }
}