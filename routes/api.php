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
Route::post('/houses', 'HouseController@store');
Route::get('/houses/{id}','HouseController@show');
Route::put('/houses/{id}', 'HouseController@update');

// Login
Route::POST('users/login', 'UserController@login');

// Logout
Route::POST('users/logout', 'UserController@logout');

// Returns the list of registered users
//Route::GET('users', "UserController@index")->middleware('auth');

// Create a user
Route::POST('users', "UserController@store")->middleware('auth');

// Return a User by ID
Route::GET('users/{id}', "UserController@show");

// Update user by ID
Route::PUT('users/{id}', "UserController@update");

// Delete a user
Route::DELETE('users/{id}', "UserController@destroy");

