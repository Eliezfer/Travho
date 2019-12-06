<?php

namespace App\Http\Controllers;

use App\Address;
use App\House;
use App\User;

use Illuminate\Http\Request;
use App\Http\Requests\HouseRequest;
use Response;

use App\Http\Resources\House as HouseResource;
use App\Http\Resources\HouseCollection;
class HouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $houses=House::orderBy('id','DESC')
        ->where('status','true')
        ->paginate(10);
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
        $this->middleware('auth:api');

        $data_address=$request['address'];
        $address=Address::create($data_address);
        $data_house["address_id"]=$address['id'];

        $data_house=$request['data'];
        $data_house["user_id"]=auth()->user()->id;
        $house=House::create($data_house);

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
