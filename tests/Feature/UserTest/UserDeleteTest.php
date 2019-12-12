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

    /**
     * DELETE-2
     * AUTENTICADO
     */

     /**
      * DELETE-3
      * AUTORIZADO
      */

      /**
       * DELETE-4
       * NOT FOUND
       */
}