<?php

namespace App\Http\Middleware;

<<<<<<< HEAD

=======
//use Response;
>>>>>>> develop
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Auth\AuthenticationException;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
<<<<<<< HEAD
        if (! $request->expectsJson()) {

            throw new AuthenticationException();
=======

        if ( !$request->expectsJson()) {

           throw new AuthenticationException();
>>>>>>> develop

        }
    }
}
