<?php

namespace App\Http\Controllers;
use App\User;
use App\House;
use App\BookingHouse;
use Illuminate\Http\Request;
use \Illuminate\Validation\Validator;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\UserCollection;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Str;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Requests\AuthRequest;


class UserController extends Controller
{

    /**
     *  User login
     *
     */
    public function login(AuthRequest $request){
        $data = $request['data']['attributes'];
        // Se filtra por email
        $user = User::where('email', $data['email'])->first();
        // No se encuentra usuario
       if($user == null){
        throw new ModelNotFoundException();
       }
       // Se muestra solamente usuarios activos
        if($user->status){
            // Se verifica el email y el password
            if($user && ($data['password'] == $user->password )){
                return response()->json([
                    'data' => [
                    'name' => $user->name,
                    'user' => $user->user,
                    'email' => $user->email,
                    'api_token' => $user->api_token
                    ],
                    'link' => [
                        'self' => config('app.url').":8000/api/v1/users/".$user->id,
                    ]
                ], 200);
                // Su contraseña no coincide
            }elseif($user && ($data['password'] != $user->password )){
                throw new AuthenticationException();
                // No existe usuario en la base de datos
            }
        }
        throw new ModelNotFoundException();
            
    }
    public function logout(AuthRequest $request){
        // Se obtiene el request
        $data = $request['data']['attributes'];
        // Se filtra por email
        $user = User::where('email', $data['email'])->first();

      // Se verifica el email y el password
      // Solamente el usuario puede cerrar su sesión
        $this->authorize('logout',$user);

        if($user != null){
            // Se verifica el email y el password
            if($user && ($data['password'] == $user->password )){
                // Se modifica el Token del usuario para que se vea 
                //obligado a inciar sesión
                $data = [
                'api_token'  =>  Str::random(80),
                ];
                $user->update($data);
                return response()->json([
                    "Sesión: " => "Logout",
                    "api_token" => $user->api_token,
                ], 200);
            
            }else{
                // Contraseña o usuario erróneo
                throw new AuthenticationException();
            }
        }elseif($user == null){ // User Status está en falso [dado de baja]
            throw new ModelNotFoundException();
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
        // $header = $request->header('api_token');
        // // $users = User::all();
        // // return response()->json($users,200);
        // return UserResource::collection(User::all());
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
        $data = $request['data']['attributes'];

        // Se crea el modelo de un usuario
        // Se genera el Token asociado al usuario
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
        // MOSTRAR SOLAMENTE SI ES ACTIVO

        // Se busca el usuario en tabla

        // Se retorna el usuario solicitado, con la representación adecuada
        if( $user != null){
             return new UserResource($user);
        }elseif($user == null){
            throw new ModelNotFoundException();
        }
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
        // SOLAMENTE SI ESTÁ ACTIVO
        $this->authorize('update',$user);
   
        // Se obtienen los datos del JSON anidado
        $data = $request['data']['attributes'];

        $filter = [
            "name" => $data['name'],
            "user" => $data['user'],
            "password" => $data['password'],
            "cellphone" => $data['cellphone'],
            "birthdate" => $data['birthdate']
        ];
        // Se guarda el user actualizado
        if($user->status){
            $user->update($filter);
            // se retorna el user modificado, con la representación anidada
            // Se retorna el user modificado, con el status 200 (OK)
             return new UserResource($user);
        }else{
            throw new ModelNotFoundException();
        }

 

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,User $user)
    {
        // COLOCAR FALSE 
        $this->authorize('delete',$user);
        if($user->status){
            $H = House::where('user_id','=',$user->id)
            -> where('status','=',true)
            ->get();

            $h = House::where('user_id','=',$user->id)
            -> where('status','=',true)
            -> update(['status' => 'false']);


            
            foreach ($H as $House){
                $b = BookingHouse::where('house_id','=',$House->id)
                ->where('status','=','in process')
                ->update(['status' => 'rejected']);
            }
            // Cambiar el estado Status a False [No borrar]
            $user->update(['status' => 'False']);

            
            return response(null,204);
        }else{
            throw new ModelNotFoundException();
        }
    }

}
