<?php

namespace App\Exceptions;

use Exception;
use Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            $json = [
                'code' => 'ERROR-2',
                'title' =>'NOT FOUND',
                'message' => 'No se encontro el recurso'
            ];
            return Response::json(['errors' =>$json], JsonResponse::HTTP_NOT_FOUND);
        }
        if ($exception instanceof AuthorizationException) {
            $json = [
                'code' => 'ERROR-3',
                'title' =>'FORBIDDEN',
                'message' => 'Usted no tiene permisos para esta acción'
            ];
            return Response::json(['errors' =>$json], JsonResponse::HTTP_FORBIDDEN);
        }
        
        return parent::render($request, $exception);
    }
}
