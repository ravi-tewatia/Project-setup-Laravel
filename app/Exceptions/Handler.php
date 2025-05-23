<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ExceptionOccurred;

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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            // IF EXCEPTIONS_LOG not found in .env then we will set default value to false
            if (env("EXCEPTIONS_LOG", false)) {
                Log::debug(
                    sprintf(
                        "\n\r%s: %s in %s:%d\n\r",
                        get_class($e),
                        $e->getMessage(),
                        $e->getFile(),
                        $e->getLine()
                    )
                );
            }

            // Send email notification for exceptions
            // $email = env('EXCEPTION_EMAIL', 'your-email@example.com');
            // Mail::to($email)->send(new ExceptionOccurred($e));
        })->stop();
    }

    public function render($request, Throwable $exception)
    {
        // Send email notification for exceptions
        // $email = env('EXCEPTION_EMAIL', 'your-email@example.com');
        // Mail::to($email)->send(new ExceptionOccurred($exception));

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again later.',
                'error' => $exception->getMessage(),
            ], 500);
        }
        return response()->view('errors.generic', ['message' => 'Something went wrong. Our team is working on it.'], 500);
    }
}
