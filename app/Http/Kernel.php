<?php

namespace App\Http;

use App\Http\Middleware\AuthenticateOnceWithBasicAuth;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Routing\Middleware\ValidateSignature;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,


            // Laravel also provides a mechanism for invalidating and "logging out" a user's sessions
            // that are active on other devices without invalidating the session on their current device.
            // This feature is typically utilized when a user is changing or updating their password and
            // you would like to invalidate sessions on other devices while keeping the current device authenticated.
            //
            // Before getting started, you should make sure that the Illuminate\Session\Middleware\AuthenticateSession
            // middleware is present and un-commented in your app/Http/Kernel.php class' web middleware group:
            \Illuminate\Session\Middleware\AuthenticateSession::class,

            // When using the AuthenticateSession middleware in combination with a custom route name for the login route,
            // you must override the unauthenticated method on your application's exception handler
            // to properly redirect users to your login page.


            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:60,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.basic.once' => \App\Http\Middleware\AuthenticateOnceWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,

        // Laravel includes a middleware that can authorize actions before the
        // incoming request even reaches your routes or controllers.
        // By default, the Illuminate\Auth\Middleware\Authorize middleware
        // is assigned the can key in your App\Http\Kernel class.
        'can' => \Illuminate\Auth\Middleware\Authorize::class,

        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,

        // By default, after confirming their password,
        // the user will not have to confirm their password again for three hours.
        // You are free to customize the length of time before the user must
        // re-confirm their password using the auth.password_timeout configuration option.
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,

        // Alternatively, you may assign the Illuminate\Routing\Middleware\ValidateSignature middleware to the route.
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,


    ];
}
