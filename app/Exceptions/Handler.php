<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

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
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

    }

    public function render($request, Throwable $e)
    {
        if($request->is('api/*')) {

            if($e instanceof AuthorizationException) {
                return response()->json(
                    [
                        'status' => 'erro', 
                        'mensagem' => 'Acesso não autorizado'
                    ], 403
                );
            }

            if($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
                return response()->json(
                    [
                        'status' => 'erro', 
                        'mensagem' => 'Não encontrado'
                    ], 404
                );
            }

            if($e instanceof MethodNotAllowedHttpException) {
                return response()->json(
                    [
                        'status' => 'erro', 
                        'mensagem' => 'Método não permitido para esta rota'
                    ], 405
                );
            }
            
            if($e instanceof ValidationException) {
                return response()->json(
                    [
                        'status' => 'erro', 
                        'mensagem' => $e->errors()
                    ], $e->status
                );
            }
            
            $detalhes = '';
            if(config('app.debug')) {
                $detalhes = ': ' . $e->getMessage() . ' ' . $e->getFile() . ' : linha ' . $e->getLine();
            }

            return response()->json(
                [
                    'status' => 'erro', 
                    'mensagem' => 'Erro no servidor' . $detalhes
                ], 500
            );
        }
    
        return parent::render($request, $e);
    }
    
}
