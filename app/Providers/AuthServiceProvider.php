<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // You may define your own authentication guards using the extend method on the Auth facade.
        // You should place this call to extend within a service provider.
        // Since Laravel already ships with an AuthServiceProvider,
        // we can place the code in that provider:

        Auth::extend('jwt', function ($app, $name, array $config) {
            // Return an instance of Illuminate\Contracts\Auth\Guard...

            return new JwtGuard(Auth::createUserProvider($config['provider']));
        });


        // The simplest way to implement a custom,
        // HTTP request based authentication system is by using the Auth::viaRequest method.
        // This method allows you to quickly define your authentication process using a single Closure.

        // To get started, call the Auth::viaRequest method within the boot method of your AuthServiceProvider.
        Auth::viaRequest('custom-token', function ($request) {
            return User::where('token', $request->token)->first();
        });
        // The viaRequest method accepts an authentication driver name as its first argument.
        // This name can be any string that describes your custom guard.
        // The second argument passed to the method should be a Closure
        // that receives the incoming HTTP request and returns a user instance or,
        // if authentication fails, null:

        // Once your custom authentication driver has been defined,
        // you use it as a driver within guards configuration of your auth.php configuration file:

        //'guards' => [
        //    'api' => [
        //        'driver' => 'custom-token',
        //    ],
        //],
        $this->addingCustomUserProviders();


    }

    /**
     * If you are not using a traditional relational database to store your users,
     * you will need to extend Laravel with your own authentication user provider.
     * We will use the provider method on the Auth facade to define a custom user provider:
     */
    public function addingCustomUserProviders()
    {
        Auth::provider('riak', function ($app, array $config) {

            // Return an instance of Illuminate\Contracts\Auth\UserProvider...

            return new RiakUserProvider($app->make('riak.connection'));
        });

        // After you have registered the provider using the provider method,
        // you may switch to the new user provider in your auth.php configuration file.
        // First, define a provider that uses your new driver:

        // 'providers' => [
        //    'users' => [
        //        'driver' => 'riak',
        //    ],
        //],
        // Finally, you may use this provider in your guards configuration:
        //
        //'guards' => [
        //    'web' => [
        //        'driver' => 'session',
        //        'provider' => 'users',
        //    ],
        //],

    }
}
