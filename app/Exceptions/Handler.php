<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\UnauthorizedException;
use Ramsey\Uuid\Exception\NameException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     * The $dontReport property of the exception handler contains an array of exception types that will not be logged.
     * For example, exceptions resulting from 404 errors,
     * as well as several other types of errors,
     * are not written to your log files.
     * You may add other exception types to this array as needed:
     * @var array
     */
    protected $dontReport = [
//        \Illuminate\Auth\AuthenticationException::class,
//        \Illuminate\Auth\Access\AuthorizationException::class,
//        \Symfony\Component\HttpKernel\Exception\HttpException::class,
//        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
//        \Illuminate\Validation\ValidationException::class,
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
     * @param \Throwable $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        //The report method is used to log exceptions or send them to an external service like Flare, Bugsnag or Sentry.
        // By default, the report method passes the exception to the base class where the exception is logged.
        // However, you are free to log exceptions however you wish.
        //
        //For example, if you need to report different types of exceptions in different ways,
        // you may use the PHP instanceof comparison operator:

        if ($exception instanceof UnauthorizedException) {
            //
        }

        // Instead of type-checking exceptions in the exception handler's report and render methods,
        // you may define report and render methods directly on your custom exception.
        // When these methods exist, they will be called automatically by the framework:

        parent::report($exception);
    }

    /**
     *
     * If available, Laravel automatically adds the current user's ID to
     * every exception's log message as contextual data.
     * You may define your own global contextual data
     * by overriding the context method of your application's App\Exceptions\Handler class.
     * This information will be included in every exception's log message written by your application:
     *
     * @return array
     */
    public function context()
    {
        return array_merge(
            parent::context(),
            [
                'foo' => 'bar',
            ]
        );
    }

    /**
     * Render an exception into an HTTP response.
     * The render method is responsible for converting a given exception
     * into an HTTP response that should be sent back to the browser.
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof NameException) {
            return response()->view('errors.custom', [], 500);
        }
        // By default, the exception is passed to the base class which generates a response for you
        return parent::render($request, $exception);
    }
}
