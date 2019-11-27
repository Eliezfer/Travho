<?php

namespace App\Http\Middleware;

use Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

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
        if (! $request->expectsJson()) {
            
            $json = [
                'code' => 'ERROR-4',
                'title' =>'UNAUTHORIZED',
                'message' => 'Consulte Autenticación de acceso básica y Autenticación de acceso resumido'
            ];
            abort(Response::json(['message' =>$json], JsonResponse::HTTP_UNAUTHORIZED));
            //return route('login');*/
        }
    }
}
