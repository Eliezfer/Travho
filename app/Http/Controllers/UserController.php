<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use \Illuminate\Validation\Validator;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\UserCollection;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(UserRequest $request)
    {
        // Get data from JSON
        $data = $request['data'];

        // Create a new product
        $user = User::create($data);

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
    public function show($id)
    {
        //
        // Se busca el usuario en tabla 
        $user = user::findOrFail($id);
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
    public function update(UserRequest $request, $id)
    {
        // Se busca el usuario en la tabla
        $user = user::findOrFail($id);
        // Se obtienen los datos del JSON anidado
        $data = $request['data'];
        // Se guarda el user actualizado
        $user->update($data);
        
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
        //
        $userToDestroy = User::findOrFail($id);
        $userToDestroy->delete();
        return response(null,204);
    }
}
