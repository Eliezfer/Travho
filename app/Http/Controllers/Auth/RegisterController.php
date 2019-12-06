<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserCollection;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */

        // Colocar modelo
        // Quitar fecha de nacimiento
    protected function create(UserRequest $request)
    {
        // Get data from JSON
        $data = $request['data'];
        
        // Generate Token API

        // Create a new product
        // PASSWORD 
        $user = User::create([
           'name' => $data['name'],
            'user' => $data['user'],
            'password' => $data['password'],
            'cellphone' => $data['cellphone'],
            'email' => $data['email'],
            'birthdate' => $data['birthdate'],
            'api_token' => Str::random(80),
        ]); 

        // Save product in the DB
        $user->save();
        //Return the JSON with specific Structure
        return new UserResource($user);
    }
}
