<?php

namespace App\Providers;

use App\Extensions\MongoSessionHandler;
use App\Jobs\ProcessPodcast;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\View\View;
use MongoDB\Driver\Query;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bindMethod(ProcessPodcast::class, '@handle', function ($job, $app) {
            return $job->handle($app->make(AudioProcessor::class));
        });
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
//        Response::marco('caps', function ($value) {
//            return Response::make(strtoupper($value));
//        });
        // The macro function accepts a name as its first argument, and a Closure as its second.
        // The macro's Closure will be executed when calling the macro name from a ResponseFactory implementation or the response helper:
        //
        //return response()->caps('foo');


        // Occasionally, you may need to share a piece of data with all views that are rendered by your application.
        // You may do so using the view facade's share method.
        // Typically, you should place calls to share within a service provider's boot method.
        // You are free to add them to the AppServiceProvider or generate a separate service provider to house them:
        \Illuminate\Support\Facades\View::share('wow', 'this is amazing, i am the shared value');


        // Once your driver has been implemented, you are ready to register it with the framework.
        // To add additional drivers to Laravel's session backend, you may use the extend method on the Session facade.
        // You should call the extend method from the boot method of a service provider.
        // You may do this from the existing AppServiceProvider or create an entirely new provider:
        Session::extend('mongo', function ($app) {
            // Return implementation of SessionHandlerInterface...
            return new MongoSessionHandler;
            // Once the session driver has been registered,
            // you may use the mongo driver in your config/session.php configuration file.
        });


        // Another method of registering custom validation rules is using the extend method on the Validator facade.
        // Let's use this method within a service provider to register a custom validation rule:
        Validator::extend('foo',
            // The custom validator Closure receives four arguments:
            function ($attribute, $value, $parameters, $validator)
                // the name of the $attribute being validated,
                // the $value of the attribute,
                // an array of $parameters passed to the rule,
                // and the Validator instance.
            {
                return $value === 'foo';
            });

        //You may also pass a class and method to the extend method instead of a Closure:
        //
        Validator::extend('foo2', 'FooValidator@validate');
        // When creating a custom validation rule,
        // you may sometimes need to define custom placeholder replacements for error messages.
        // You may do so by creating a custom Validator as described above then
        // making a call to the replacer method on the Validator facade.
        // You may do this within the boot method of a service provider:
        Validator::replacer('foo', function ($message, $attribute, $rule, $parameters) {
            return str_replace('haha', 'hehe', $message);
        });

        // By default, when an attribute being validated is not present or contains an empty string,
        // normal validation rules,
        // including custom extensions, are not run.
        // For example, the unique rule will not be run against an empty string:
        //
        //$rules = ['name' => 'unique:users,name'];
        //
        //$input = ['name' => ''];
        //
        //Validator::make($input, $rules)->passes(); // true
        // For a rule to run even when an attribute is empty,
        // the rule must imply that the attribute is required.
        // To create such an "implicit" extension, use the Validator::extendImplicit() method:

        Validator::extendImplicit('foo', function ($attribute, $value, $parameters, $validator) {
            return $value === 'foo';
        });
        // An "implicit" extension only implies that the attribute is required.
        // Whether it actually invalidates a missing or empty attribute is up to you.


        //注册collection 宏 后面可以在一个collection实例上调用toUpper方法了。
        Collection::macro('toUpper', function () {
            return $this->map(function ($value) {
                return Str::upper($value);
            });
        });


        //Queue Job failed event triggered
        Queue::failing(function (JobFailed $event) {
            // $event->connectionName
            // $event->job
            // $event->exception
        });


        Queue::before(function (JobProcessing $event) {
            // $event->connectionName
            // $event->job
            // $event->job->payload()
        });
        Queue::after(function (JobProcessed $event) {
            // $event->connectionName
            // $event->job
            // $event->job->payload()
        });

        Queue::looping(function () {
            while (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
        });
    }
}
