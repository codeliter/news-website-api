<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($request->is('api/*')) {
            return $this->handleAPIExceptions($request, $exception);
        }
        return parent::render($request, $exception);
    }

    /**
     * @param $request
     * @param $exception
     * @return \Illuminate\Http\Response|mixed
     */
    private function handleAPIExceptions($request, $exception)
    {

        if ($exception instanceof HttpException) {
            return $this->respondWithError(httpStatusText($exception->getStatusCode()), $exception->getStatusCode());
        } else if ($exception instanceof ValidationException) {
            return $this->respondWithError(collect($exception->validator->getMessageBag())->flatten(), 422);
        }

        return parent::render($request, $exception);
    }


    /**
     * respond with a generic error
     * @param string $message
     * @param $status_code
     * @return mixed
     */
    public function respondWithError($message, $status_code)
    {
        return response()->json([
            'error' => true,
            'errors' => $message,
            'status_code' => $status_code
        ], $status_code);

    }
}
