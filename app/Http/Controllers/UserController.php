<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use \Illuminate\Validation\Validator;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\UserCollection;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Str;
use Illuminate\Auth\AuthenticationException;


class UserController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    // }
    /**
     *  User login
     * 
     */
    public function login(Request $request){
        $data = $request['data'];
        // Se filtra por email
        $user = User::where('email', $data['email'])->first();

        // Se verifica el email y el password 
        if($user && ($data['password'] == $user->password )){
            return response()->json([
                'data' => [
                'name' => $user->name,
                'user' => $user->user,
                'email' => $user->email,
                'api_token' => $user->api_token
                ]
            ], 200);
        }else{
            // Mensaje de error 
            return response()->json(["error" => "No content"],406);
        }
                

    }
 
    public function logout(Request $request){
        $data = $request['data'];
        // Se filtra por email
        $user = User::where('email', $data['email'])->first();
        
        // Solamente el usuario puede cerrar su sesión

        $this->authorize('logout',$user);

       
        // Se verifica el email y el password 
        if($user && ($data['password'] == $user->password )){
            
            $data = [
             'api_token'  =>  Str::random(80),
            ];
            $user->update($data);
            return response()->json([
                "Sesión: " => "Logout",
                "api_token" => $user->api_token,
            ], 200);
        }

    }

    /**
     * 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request)
    {
        $header = $request->header('api_token');
        // $users = User::all();
        // return response()->json($users,200);
        return UserResource::collection(User::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function store(UserRequest $request)
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

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        
        // 
        //$header = $request->header('api_token');
        //
        // Se busca el usuario en tabla 
        // Handler
         //$user = user::findOrFail($id);
        // Se retorna el usuario solicitado, con la representación adecuada
        return new UserResource($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $this->authorize('update',$user);
        //$this->middleware('auth:api');
        // Solamente el usuario puede actualizar su información [Policies]
        //$header = $request->header('api_token');
        // Se busca el usuario en la tabla
      // $user = user::findOrFail($id);
        // Se obtienen los datos del JSON anidado
        $data = $request['data'];
        
        $filter = [
            "name" => $data['name'],
            "user" => $data['user'],
            "password" => $data['password'],
            "cellphone" => $data['cellphone'],
            "birthdate" => $data['birthdate']
        ];
        // Se guarda el user actualizado
        $user->update($filter);
        
        // Se retorna el user modificado, con el status 200 (OK)
        // return response()->json($user,200);

        // se retorna el user modificado, con la representación anidada
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('delete',$user);
       // $header = $request->header('api_token');
        // Solamente el mismo usuario puede destruir su usuario
        //$userToDestroy = User::findOrFail($id);
        $userToDestroy->delete();
        return response(null,204);
    }
}
