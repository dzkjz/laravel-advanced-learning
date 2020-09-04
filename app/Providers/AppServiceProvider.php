<?php

namespace App\Providers;

use App\Events\UserDeleted;
use App\Extensions\MongoSessionHandler;
use App\Jobs\ProcessPodcast;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;
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


        DB::listen(function ($query) {
            // $query->sql;
            // $query->bindings;
            // $query->time;
        });


        Paginator::defaultView('view-name');

        Paginator::defaultSimpleView('view-name');

//        Using Tailwind

        Paginator::useTailwind();


        // Laravel uses the utf8mb4 character set by default,
        // which includes support for storing "emojis" in the database.
        // If you are running a version of MySQL older than the 5.7.7 release or
        // MariaDB older than the 10.2.2 release,
        // you may need to manually configure the default string length
        // generated by migrations in order for MySQL to create indexes for them.
        // You may configure this by calling the Schema::defaultStringLength method within your AppServiceProvider:
        Schema::defaultStringLength(191);
        // Alternatively, you may enable the innodb_large_prefix option for your database.
        // Refer to your database's documentation for instructions on how to properly enable this option.


        //注册观察者
        // To register an observer, use the observe method on the model you wish to observe.
        // You may register observers in the boot method of one of your service providers.
        // In this example, we'll register the observer in the AppServiceProvider:
        User::observe(UserObserver::class); //模型使用observe方法，参数为对应的observer class


        //自定义多态类型

        // 默认情况下， Laravel 使用完全限定类名存储关联模型类型。
        //在上面的一对多示例中， 因为 Image 可能从属于一个 Student 或一个 Teacher，默认的 imageable_type 就将分别是 App\Models\Student
        // 或 App\Models\Teacher。
        //不过，你可能希望数据库与应用的内部结构解耦。在这种情况下，可以定义一个 「morph 映射」 来通知 Eloquent 使用自定义名称代替对应的类名：

        // 注意：在现有应用程序中添加「morph 映射」时，数据库中仍包含完全限定类的每个可变形 *_type 列值都需要转换为其「映射」名称。
        Relation::morphMap(
            [
                'students'=>Student::class,
                'teachers'=>Teacher::class,
            ]
        );

    }
}
