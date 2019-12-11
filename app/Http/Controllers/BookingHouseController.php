<?php

namespace App\Http\Controllers;

use App\BookingHouse;
use Illuminate\Http\Request;
use Response;
use App\Http\Requests\BookingHouseCreateRequest;
use App\Http\Requests\BookingHouseUpdateRequest;
use App\Http\Resources\BookingHouse as BookingHouseResource;
use App\Http\Resources\BookingHouseCollection as BookingHouseResourceCollection;
use App\House;

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
        $bookingsHouse = BookingHouse::MyBokings();
        return new  BookingHouseResourceCollection($bookingsHouse,200);
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
        $attributes=$request->input('data.attributes');
        $this->authorizeStore($attributes);
        $attributes['user_id']=auth()->user()->id;
        $attributes['status'] = 'in process';
        $bookingHouse = BookingHouse::create($attributes);
        return new BookingHouseResource($bookingHouse,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BookingHouse  $bookingHouse
     * @return \Illuminate\Http\Response
     */
    public function show(BookingHouse $bookingHouse)
    {
        $this->authorize('view',$bookingHouse);
        return new BookingHouseResource($bookingHouse);
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
    public function update(BookingHouseUpdateRequest $request, BookingHouse $bookingHouse)
    {
        $this->authorizeUpdate($bookingHouse);

        $house=House::findorfail($bookingHouse->house_id);
        $statusRequest = $request->has('data.attributes.status')?
                        $request->input('data.attributes.status')
                        :'none';
        if(auth()->user()->id == $house->user_id){
            $this->authorize('updateBookingAccepted',$bookingHouse);
            if( $statusRequest=='accepted'){
                BookingHouse::BookingsBetweenDate($bookingHouse->check_in,$bookingHouse->check_out)
                ->UpdateBookingsToCancel($bookingHouse->house_id);
            }
            $bookingHouse->status = $statusRequest;
        }
        if(auth()->user()->id == $bookingHouse->user_id){
            $this->authorize('updateBookingRejected',$bookingHouse);

            if(($statusRequest == 'canceled') && ($bookingHouse->status == 'accepted') ){
                $this->authorize('updateBookingToCancel',$bookingHouse);
                $bookingHouse->status = $statusRequest;
            }

            if($statusRequest == 'canceled' ){
                $bookingHouse->status = $statusRequest;
            }

            if($bookingHouse->status == 'in process'){
                $chekInRequest=$request->input('data.attributes.check_in');
                $checkOutRequest =$request->input('data.attributes.check_out');
                $this->authorizeDate($bookingHouse->house_id,$chekInRequest,$checkOutRequest);
                $bookingHouse->check_in = $chekInRequest;
                $bookingHouse->check_out = $checkOutRequest;
            }

            if($bookingHouse->status == 'accepted'){
                return $this->updateBookingAccepted($bookingHouse, $request);
            }

        }
        $bookingHouse->save();
        return new BookingHouseResource($bookingHouse);
    }

    public function updateBookingAccepted($bookingHouse, $request){
        $bookingHouse->status = 'canceled';
                $bookingHouse->save();
                $input = $request->input('data.attributes');
                $input['house_id'] = $bookingHouse->house_id;
                return $this->store(new BookingHouseCreateRequest($input));
    }

    public function authorizeUpdate($bookingHouse){
        $this->authorize('update',$bookingHouse);
        $this->authorize('updatePastBookingDate',$bookingHouse);
        $this->authorize('updateBookingCanceled',$bookingHouse);
    }

    public function authorizeStore($input){
        $house=House::findorfail($input['house_id']);
        $this->authorize('create',[BookingHouse::class, $house]);
        $this->authorize('isHouseAvailable',[BookingHouse::class, $house]);
        $this->authorizeDate($input['house_id'],$input['check_in'],$input['check_out']);
    }

    public function authorizeDate($houseId, $checkIn, $checkOut){
        $bookingsAccept = BookingHouse::BookingsAccept($houseId)
                        ->BookingsBetweenDate($checkIn,$checkOut)->get();
        $this->authorize('isHouseAvailableToTheDate',[BookingHouse::class, $bookingsAccept]);
    }

}
