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

Route::middleware('auth:api')->get('/users', 'UserController@index');

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
Route::get('/houses','HouseController@index'); //'implementar el filtrado con query buider
Route::middleware('auth:api')->post('/houses', 'HouseController@store');
Route::get('/houses/{house}','HouseController@show');
Route::middleware('auth:api')->put('/houses/{house}', 'HouseController@update');

// Login
Route::POST('users/login', 'UserController@login');



// Logout
Route::middleware('auth:api')->post('users/logout', 'UserController@logout');

// Returns the list of registered users
//Route::GET('users', "UserController@index")->middleware('auth');

// Create a user
Route::POST('users', "UserController@store");
// Return a User by ID
Route::middleware('auth:api')->get('users/{user}', "UserController@show");


// Update user by ID
Route::middleware('auth:api')->put('users/{user}', "UserController@update");

// Delete a user
Route::middleware('auth:api')->delete('users/{user}', "UserController@destroy");

