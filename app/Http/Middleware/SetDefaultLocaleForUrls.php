<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\URL;

class SetDefaultLocaleForUrls
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // You may wish to call this method from a route middleware so that you have access to the current request:
        URL::defaults(['locale' => $request->user()->locale]);

        // Once the default value for the locale parameter has been set,
        // you are no longer required to pass its value when generating URLs via the route helper.
        return $next($request);
    }
}
