<?php

namespace App\Providers;

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        // If you would like to define a custom response that you can
        // re-use in a variety of your routes and controllers,
        // you may use the macro method on the Response facade.
        // For example, from a service provider's boot method:
        Response::marco('caps', function ($value) {
            return Response::make(strtoupper($value));
        });
        // The macro function accepts a name as its first argument, and a Closure as its second.
        // The macro's Closure will be executed when calling the macro name from a ResponseFactory implementation or the response helper:
        //
        //return response()->caps('foo');
    }
}
