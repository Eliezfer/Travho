<?php

namespace App\Http\Controllers;

use App\BookingHouse;
use Illuminate\Http\Request;
use Response;
use App\Http\Requests\BookingHouseCreateRequest;

class BookingHouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Response::json(BookingHouse::all(),200);
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
        $bookingHouse = BookingHouse::create($request->all());
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
