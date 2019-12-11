<?php

namespace App\Http\Controllers;

use App\Address;
use App\House;
use App\User;
use App\BookingHouse;

use Illuminate\Http\Request;
use App\Http\Requests\HouseRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\House as HouseResource;
use App\Http\Resources\HouseCollection;
use Facade\Ignition\QueryRecorder\Query;
use Illuminate\Support\Collection;

class HouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query=$request->query();

        $housesQuery=House::where('status','=','true');
        //si la variable State se encuentra en la URL complemnta el Query a la base de datos
        if($request->query('state', 'false')!='false'){
            $housesQuery= $housesQuery->where('state',$query['state']);
        }

        //Realiza la consulta y ordena de forma decendednte con respecto a la Id con paginación
        $houses=$housesQuery->orderBy('id','DESC')->paginate(10);

        //Incluye la variable state a la url
        if($request->query('state', 'false')!='false'){
            $houses->withPath('/api/houses?state='.$query['state']);
        }
        return new HouseCollection($houses);

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
    public function store(HouseRequest $request)
    {

        $query_string=$request->query();
        $data_address=$request['address'];

        $data_house=$request['data'];
        $address=Address::create($data_address);
        $data_house["address_id"]=$address['id'];

       //obtener la id del usuario mediante su token
       $user= User::select('id')->where('api_token','=',$query_string['api_token'])->get()[0];

       $data_house["user_id"]=$user['id'];

        $house=House::create($data_house);
        return $house;
        return new HouseResource($house);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\House  $house
     * @return \Illuminate\Http\Response
     */
    public function show(House $house)
    {
        return new HouseResource($house);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\House  $house
     * @return \Illuminate\Http\Response
     */
    public function edit(House $house)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\House  $house
     * @return \Illuminate\Http\Response
     */
    public function update(House $house,HouseRequest $request)
    {
      //verifica que este autenticado

        $this->authorize('update',$house);
        $data_address=$request['address'];
        $data_house=$request['data'];

        $house->update($data_house);
        $house->address->update($data_address);

         return new HouseResource($house);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\House  $house
     * @return \Illuminate\Http\Response
     */
    public function destroy(House $house)
    {
        $this->authorize('delete',$house);
        //Busca los booking de las casas a eliminar y los cancela
        BookingHouse::where('house_id','=',$house['id'])
        ->where('status','=','in process')
        ->update(['status'=>'rejected']);
        //da de baja la casa

        $house->update(['status'=>'false']);
        return response("",204);
    }
}
