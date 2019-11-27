<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * Create booking
 */
Route::post('bookings', 'BookingHouseController@store');

/**
 * Get a list of bookings
 */
Route::get('bookings', 'BookingHouseController@index');

/**
 * Get a booking
 */
Route::get('bookings/{bookingHouse}', 'BookingHouseController@show');

/**
 * Edit a booking
 */
Route::put('booking/{bookingHouse}', 'BookingHouseController@update');