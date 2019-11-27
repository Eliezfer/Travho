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

Route::get('/houses','HouseController@index'); //'implementar el filtrado con query buider
Route::post('/houses', 'HouseController@store');
Route::get('/houses/{id}','HouseController@show');
Route::put('/houses/{id}', 'HouseController@update');

// Returns the list of registered users
Route::GET('users', "UserController@index");

// Create a user
Route::POST('users', "UserController@store");

// Return a User by ID
Route::GET('users/{id}', "UserController@show");

// Update user by ID
Route::PUT('users/{id}', "UserController@update");

// Delete a user
Route::DELETE('users/{id}', "UserController@destroy");

