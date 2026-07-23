<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;
use App\Support\ApiResponse;

class Handler extends ExceptionHandler
{
    use ApiResponse;

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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $e)
    {
        // If the request expects JSON or is an API request, return JSON response
        if ($this->isApiRequest($request)) {
            return $this->renderApiException($request, $e);
        }

        // For non-API requests, use the parent render method
        return parent::render($request, $e);
    }

    /**
     * Determine if the request is an API request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function isApiRequest(Request $request): bool
    {
        return $request->expectsJson() || $request->is('api/*');
    }

    /**
     * Render an exception into a JSON API response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function renderApiException(Request $request, Throwable $e)
    {
        // Handle validation exceptions
        if ($e instanceof ValidationException) {
            return $this->apiError(
                'Validation failed',
                $e->errors(),
                422
            );
        }

        // Handle authentication exceptions
        if ($e instanceof AuthenticationException) {
            return $this->apiError(
                'Unauthenticated.',
                [],
                401
            );
        }

        // Handle authorization exceptions
        if ($e instanceof AuthorizationException) {
            return $this->apiError(
                'Unauthorized.',
                [],
                403
            );
        }

        // Handle model not found exceptions
        if ($e instanceof ModelNotFoundException) {
            $model = strtolower(class_basename($e->getModel()));
            return $this->apiError(
                "No query results for model [{$model}].",
                [],
                404
            );
        }

        // Handle HTTP exceptions (e.g., NotFound, MethodNotAllowed, etc.)
        if ($e instanceof HttpException) {
            return $this->apiError(
                $e->getMessage(),
                [],
                $e->getStatusCode()
            );
        }

        // Handle token mismatch exceptions
        if ($e instanceof TokenMismatchException) {
            return $this->apiError(
                'Page expired due to inactivity. Please refresh and try again.',
                [],
                419
            );
        }

        // Handle post too large exceptions
        if ($e instanceof PostTooLargeException) {
            return $this->apiError(
                'The uploaded file exceeds the maximum allowed size.',
                [],
                413
            );
        }

        // For all other exceptions, return a generic error
        // In production, we don't want to expose detailed error messages
        if (app()->environment('production')) {
            return $this->apiError(
                'An unexpected error occurred.',
                [],
                500
            );
        }

        // In development, we might want to show more details
        return $this->apiError(
            $e->getMessage(),
            ['exception' => get_class($e), 'file' => $e->getFile(), 'line' => $e->getLine(), 'trace' => $e->getTrace()],
            500
        );
    }
}