<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
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

     /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        if($exception instanceof AuthenticationException)
        {
            if ($request->is('api/*')) {

                return response()->json([
                    'status' => -1,
                    'result' => new \stdClass(),
                    'message' => 'Unauthorized',
                ], 401);
            }
        }


        // if($exception instanceof ModelNotFoundException)
        // {
        //     if ($request->is('api/*')) {

        //         return response()->json([
        //             'status' => -1,
        //             'result' => new \stdClass(),
        //             'message' => 'Unauthorized',
        //         ], 401);
        //     }
        // }

        return parent::render($request, $exception);
    }

         /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        $path = explode('/', $request->path());

        if($path[0] == 'api'){
            return  response()->json([
                'status' => -1,
                'result' => new \stdClass(),
                'message' => $exception->getMessage()], 401);
        } else{

            if ($request->is('admin') || $request->is('admin/*')) {
                return redirect()->guest(route('adminLogin'));
            }

            if ($request->is('hr') || $request->is('hr/*')) {
                return redirect()->guest(route('hrLogin'));
            }

            if ($request->is('marketing-admin') || $request->is('marketing-admin/*')) {
                return redirect()->guest(route('maLogin'));
            }
            return redirect()->guest(route('adminLogin'));
        }
    }

      /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {

        $path = explode('/', $request->path());
        $isAjax = !empty($path[0]) && $path[0]== 'api' ? true : false;

        if ($e->response) {
            return $e->response;
        }

        return ($request->expectsJson() || $isAjax)
        ? $this->invalidJson($request, $e)
        : $this->invalid($request, $e);
    }


       /**
     * Convert a validation exception into a JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Validation\ValidationException  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        $errors = collect($exception->errors())->first();

        $message = '';

        if(!empty($errors[0]))
        {
            $message = $errors[0];
        }
        return response()->json([
            'status' => 0,
            'result' => new \stdClass(),
            'message' => $message,
        ], 200);
    }
}
