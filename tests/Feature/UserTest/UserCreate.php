<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
//use Illumintae\Foundation\Http\FormRequest;
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
    public function test_client_can_()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
