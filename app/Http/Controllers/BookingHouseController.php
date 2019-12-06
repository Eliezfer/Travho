<?php

namespace App\Http\Controllers;

use App\BookingHouse;
use Illuminate\Http\Request;
use Response;
use App\Http\Requests\BookingHouseCreateRequest;

class BookingHouseController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $bookingsHouse = BookingHouse::orderBy('id','DESC')
            ->where('user_id', auth()->user()->id )
            ->paginate(10);
        return Response::json($bookingsHouse,200);
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
    public function store(BookingHouseCreateRequest $request)
    {
        //
        $input=$request->all();
        $input['user_id']=auth()->user()->id ;
        $bookingHouse = BookingHouse::create($input);
        return Response::json($bookingHouse,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BookingHouse  $bookingHouse
     * @return \Illuminate\Http\Response
     */
    public function show(BookingHouse $bookingHouse)
    {
        //
        $this->authorize('view',$bookingHouse);
        return $bookingHouse;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BookingHouse  $bookingHouse
     * @return \Illuminate\Http\Response
     */
    public function edit(BookingHouse $bookingHouse)
    {
        //
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BookingHouse  $bookingHouse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BookingHouse $bookingHouse)
    {
        //
        //BookingHouse->status()
        $attribute = $request->all();
        $bookingHouse->update($attribute);
        return $bookingHouse;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BookingHouse  $bookingHouse
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookingHouse $bookingHouse)
    {
        //
    }
}
