<?php

namespace LocalheroPortal\Core\Exceptions;

use Exception;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class Handler extends ExceptionHandler
{
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
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [];

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request                                                            $request
     * @param  Exception                                                          $exception
     * @return Response|JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof HttpExceptionInterface) {
            if (view()->exists('errors.' . $exception->getStatusCode())) {
                return response()->view('errors.' . $exception->getStatusCode(), [], $exception->getStatusCode());
            }
        }
        if ($exception instanceof MaintenanceModeException) {
            return response()->view('errors.503', ['message' => $exception->getMessage()], $exception->getStatusCode(), $exception->getHeaders());
        }
        if ($exception instanceof ValidationException) {
            return response()->json(['message' => __("The given Data was invalid."), 'errors' => $exception->validator->getMessageBag()], 422);
        }

        return parent::render($request, $exception);
    }

    /**
     * Report or log an exception.
     *
     * @param Exception $exception
     */
    public function report(Throwable $exception)
    {
        if (app()->bound('sentry') && $this->shouldReport($exception)) {
            app('sentry')->captureException($exception);
        }

        parent::report($exception);
    }
}
