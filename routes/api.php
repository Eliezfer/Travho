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

