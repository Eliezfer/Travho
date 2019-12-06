<?php

namespace App\Http\Controllers;

use App\Address;
use App\House;
use App\User;

use Illuminate\Http\Request;
use App\Http\Requests\HouseRequest;

use App\Http\Resources\House as HouseResource;
use App\Http\Resources\HouseCollection;
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
        if(empty($query)){
            $houses=House::get();
            return new HouseCollection($houses);

        }
        //$houses=House::select('address_id')->get();
        $address=Address::select('id')->where('state','=','yucatan')->get();
        foreach($address as $add){
            $houses[]=House::findOrfail($add['id']);
        }

        $housesCollection=Collection::make($houses);
        return new HouseCollection($housesCollection);

        //return $houses;
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
       $data_address=$request['address'];
       $data_house=$request['data'];
       $address=Address::create($data_address);
       $data_house["address_id"]=$address['id'];
       //Esto se cambia con la validacion de token para saber el id del usuario
       $user=User::findorfail(1);
       //bla bla
       $data_house["user_id"]=1;
       $house=House::create($data_house);
       return new HouseResource($house);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\House  $house
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $house=House::findorfail($id);
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
    public function update($id,HouseRequest $request)
    {
        $data_address=$request['address'];
        $data_house=$request['data'];

        $house=House::findorfail($id);

        $address=Address::findorfail($house['address_id']);
        $house->update($data_house);
        $address->update($data_address);

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
        //
    }
}
