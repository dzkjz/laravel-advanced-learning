<?php

namespace App\Http\Controllers;

use App\Contract\UserRepositoryInterface;
use App\Events\PodcastProcessed;
use App\Models\Podcast;
use App\Models\Post;
use http\Client\Curl\User;
use Illuminate\Cache\Events\CacheHit;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Str;
use Psy\VersionUpdater\GitHubChecker;

class UserController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    private $users;

    public function __construct(UserRepositoryInterface $users)
    {
        $this->users = $users;

    }

    public function getIndex()
    {
//        $reflection = new \ReflectionClass(StripeBiller::class);
//        dd($reflection->getMethods());
//        dd($reflection->getNamespaceName());
//        dd($reflection->getProperties());

        $users = $this->users->all();
        return View::make('users.index', compact('users'));
    }

    public function cacheTest()
    {
        return response("Haha")
            ->setClientTtl(3600)
            ->setPublic()//            ->setExpires(new \DateTime(date(DATE_RFC7231, time() + 3600)))
            ;
    }

    public function test(Request $request)
    {
        $request->path();
        if ($request->is('admin/*')) {

        }
        $request->url();
        $request->fullUrl();
        $request->getMethod();

        $request->method();


        if ($request->isMethod('POST')) {
            echo "Hello";
        }
        $request->all();
        $request->input();
        $request->input('name');
        $request->input('name', 'DefaultName');
        $name = $request->input('products.0.name');
        $names = $request->input('products.*.name');//wildcard array input form fields


        $request->query();
        $request->query('name');
        $request->query('name', 'DefaultName');

        $request->name;
        // When using dynamic properties, Laravel will first look for the parameter's value in the request payload.
        // If it is not present, Laravel will search for the field in the route parameters.

        $request->get('name');

        $request->input('user.name');//json

        $request->boolean('archived');

        $request->only(['username', 'password']);
        $request->except(['credit_card']);


        if ($request->has('name')) {

        } elseif ($request->has(['password', 'email'])) {

        } elseif ($request->hasAny(['password_confirmation', 'remember'])) {

        } elseif ($request->filled('name')) {//value is present and not empty

        } elseif ($request->missing('password')) {//key is absent from the request

        }

        // Laravel allows you to keep input from one request during the next request.
        // This feature is particularly useful for re-populating forms after detecting validation errors.
        // However, if you are using Laravel's included validation features,
        // it is unlikely you will need to manually use these methods,
        // as some of Laravel's built-in validation facilities will call them automatically.
        $request->flash();
        $request->flashOnly(['name', 'password']);
        $request->flashExcept(['email']);
        if ($request->has(['hha'])) {
//            return redirect('form')->withInput();
            return redirect('form')->withInput(
                $request->except('password')
            );

        } elseif ($request->has('hhha')) {
            $username = $request->old('username');
        }
        $cookie_name = $request->cookie('name');
        $cookie_name = Cookie::get('name');
        if ($request->has(['hha'])) {
            $minutes = 10;
            return response('Hello world!')
                // You should pass the name, value, and number of minutes the cookie should be considered valid to this method:
                ->cookie('name', 'cookie_name', $minutes);

        } elseif ($request->has('hhha')) {
            // The cookie method also accepts a few more arguments which are used less frequently.
            // Generally, these arguments have the same purpose and meaning as the arguments that would be given to PHP's native setcookie method:
            $minutes = 10;
            $path = 'App/Illustrate';
            $domain = '*.de';
            $secure = true;
            $httpOnly = false;
            return response('Hello World')->cookie(
                'name', 'cookie_name', $minutes, $path, $domain, $secure, $httpOnly
            );
        } else if ($request->has('alls')) {
            // Alternatively, you can use the Cookie facade to "queue" cookies for attachment to the outgoing response from your application.
            // The queue method accepts a Cookie instance or the arguments needed to create a Cookie instance.
            // These cookies will be attached to the outgoing response before it is sent to the browser:
            $minutes = 10;
            Cookie::queue(
            // Cookie instance
                Cookie::make('name', 'cookie_name', $minutes)
            );
            Cookie::queue('name', 'cookie_name', $minutes);

        } else if ($request->has('allas')) {
            //If you would like to generate a Symfony\Component\HttpFoundation\Cookie instance that can be given to a response instance at a later time,
            // you may use the global cookie helper.
            //This cookie will not be sent back to the client unless it is attached to a response instance:
            $minutes = 10;
            $cookie = \cookie('name', 'cookie_name', $minutes);
            return response('Hello China!')->cookie($cookie);
        } else if ($request->has('allis')) {
            //You may remove a cookie by expiring it via the forget method of the Cookie facade:
//            Cookie::queue(Cookie::forget('name'));

//Alternatively, you may attach the expired cookie to a response instance:
            $cookie = Cookie::forget('name');
            return response('Hello Cookie')->withCookie($cookie);
        }


        $request->file('photo');
        $request->photo;

        if ($request->hasFile('photo')) {
            //
        }

        if ($request->file('photo')->isValid()) {
            //
        }

        $request->file('avatar')->path();
        $request->avatar->path();


        $request->file('avatar')->extension();
        $request->avatar->extension();

        $request->file('photo')->store('images');
        $request->file('photo')->store('images', 's3');
        $request->photo->store('images', 's3');

        $custom_file_name = 'Random_name';
        $request->file('photo')->storeAs('images', $custom_file_name);
        $request->file('photo')->storeAs('images', $custom_file_name, 's3');


    }

    public function responseTest(Request $request)
    {
        $variable = 0;
        $type = 'text/plain';
        $minutes = 10;
        if ($variable === 1) {
            return response('Hello world', 200)
                ->header('Content-Type', 'text/plain');
        } elseif ($variable === 2) {

            return response('Hello world', 200)
                ->withHeaders([
                    'X-Header-One' => 'Header Value',
                    'X-Header-Two' => 'Header Value',
                    'Content-Type' => $type,
                ])
                ->header('Whatever', 'SomeValue');
        } elseif ($variable === 3) {
            return response($variable)
                ->header('Content-Type', $type)
                ->cookie('name', 'value', $minutes);
        } elseif ($variable === 4) {
            return redirect('home/dashboard');
        } elseif ($variable === 5) {
            return back()->withInput();
        } elseif ($variable === 6) {
            return redirect()->route('login');
        } elseif ($variable === 7) {
            // For a route with the following URI: profile/{id}
            return redirect()->route('profile', ['id' => 1]);
        } elseif ($variable === 8) {
            $user = \App\User::find(1);

            //If you are redirecting to a route with an "ID" parameter that is being populated from an Eloquent model,
            // you may pass the model itself.
            // The ID will be extracted automatically:

            // For a route with the following URI: profile/{id}

            return redirect()->route('profile', [$user]);
        } elseif ($variable === 9) {
            $user = \App\User::find(1);
            //If you are redirecting to a route with an "ID" parameter that is being populated from an Eloquent model,
            // you may pass the model itself.
            // The ID will be extracted automatically:

            // For a route with the following URI: profile/{id:slug}
            return redirect()->route('profle', [$user]);

            //or you can override the getRouteKey method on your Eloquent model:
            //
            ///**
            // * Get the value of the model's route key.
            // *
            // * @return mixed
            // */
            //public function getRouteKey()
            //{
            //    return $this->slug;
            //}
        } elseif ($variable === 10) {
            //You may also generate redirects to controller actions.
            // To do so, pass the controller and action name to the action method.
            return redirect()->action('HomeController@index');
        } elseif ($variable === 11) {

            //If your controller route requires parameters,
            // you may pass them as the second argument to the action method:
            return redirect()->action(
                'UserController@profle',
                ['id' => 2]
            );
        } elseif ($variable === 12) {
            // Sometimes you may need to redirect to a domain outside of your application.
            // You may do so by calling the away method,
            // which creates a RedirectResponse without any additional URL encoding, validation, or verification:
            return redirect()->away('https://www.google.com');
        } elseif ($variable === 13) {
            return redirect('dashboard')
                ->with('status', 'Profile updated!');
            // After the user is redirected, you may display the flashed message from the session. For example, using Blade syntax:
            //
            //@if (session('status'))
            //    <div class="alert alert-success">
            //        {{ session('status') }}
            //    </div>
            //@endif
        } elseif ($variable === 14) {
            $data = '';
            return response()
                // When the response helper is called without arguments,
                // an implementation of the Illuminate\Contracts\Routing\ResponseFactory contract is returned.
                // This contract provides several helpful methods for generating responses.
                ->view('hello', $data, 200)
                // Of course, if you do not need to pass a custom HTTP status code or custom headers,
                // you should use the global view helper function.
                ->header('Content-Type', $type);
            // Of course, if you do not need to pass a custom HTTP status code or custom headers, you should use the global view helper function.
        } elseif ($variable === 15) {
            // The json method will automatically set the Content-Type header to application/json,
            // as well as convert the given array to JSON using the json_encode PHP function:
            return response()->json([
                'name' => 'Abigail',
                'state' => 'CA'
            ]);
        } elseif ($variable === 16) {
            // If you would like to create a JSONP response,
            // you may use the json method in combination with the withCallback method:
            return response()
                ->json(['name' => 'Abigail', 'state' => 'CA'])
                ->withCallback($request->input('callback'));
        } elseif ($variable === 17) {
            // The download method may be used to generate a response that forces the user's browser to download the file at the given path.
            // The download method accepts a file name as the second argument to the method,
            // which will determine the file name that is seen by the user downloading the file.
            // Finally, you may pass an array of HTTP headers as the third argument to the method:
            $pathToFile = '';
            // Symfony HttpFoundation, which manages file downloads, requires the file being downloaded to have an ASCII file name.
            $name = '';
            $headers = ['a' => '1'];
//            return response()->download($pathToFile);
//            return response()->download($pathToFile, $name, $headers);
            return response()->download($pathToFile, $name, $headers)->deleteFileAfterSend();
//            return response()->download($pathToFile)->deleteFileAfterSend();
        } elseif ($variable === 18) {

            // Sometimes you may wish to turn the string response of a given operation into a downloadable response without having to write the contents of the operation to disk.
            // You may use the streamDownload method in this scenario.
            // This method accepts a callback, file name, and an optional array of headers as its arguments:

            return response()->streamDownload(function () {
                echo GitHub::api('repo')
                    ->contents()
                    ->readme('laravel', 'laravel')['contents'];
            }, 'laravel-readme.md');
        } elseif ($variable === 19) {
            // The file method may be used to display a file, such as an image or PDF, directly in the user's browser instead of initiating a download.
            // This method accepts the path to the file as its first argument and an array of headers as its second argument:
            $pathToFile = '';
            $headers = ['a' => '1'];
            return response()->file($pathToFile, $headers);
        } elseif ($variable === 20) {
            return response()->caps('foo');
        }

    }

    public function viewsTest(Request $request)
    {
        $variable = '';
        if ($variable === 1) {
            // Since this view is stored at resources/views/greeting.blade.php,
            // we may return it using the global view helper like so:
            return \view('greeting', ['name' => 'JimmyT']);
            // As you can see, the first argument passed to the view helper corresponds
            // to the name of the view file in the resources/views directory.

            // The second argument is an array of data that should be made available to the view.
            // In this case, we are passing the name variable, which is displayed in the view using Blade syntax.
        } elseif ($variable === 2) {
            $data = ['name' => 'Cathy', 'age' => '22'];
            // Views may also be nested within subdirectories of the resources/views directory.
            // "Dot" notation may be used to reference nested views.
            // For example, if your view is stored at resources/views/admin/profile.blade.php, you may reference it like so:

            return \view('admin.profile', $data);
            // View directory names should not contain the . character.
        } elseif ($variable === 3) {
            if (View::exists('emails.customer')) {
                // If you need to determine if a view exists, you may use the View facade.
                // The exists method will return true if the view exists:
            } else {
                $data = ['name' => 'Cathy', 'age' => '22'];
                // Using the first method, you may create the first view that exists in a given array of views.
                // This is useful if your application or package allows views to be customized or overwritten:
//                return view()->first(['custom.admin', 'admin'], $data);
                // You may also call this method via the View facade:
                return View::first(['custom.admin', 'admin'], $data, []);
            }

        } elseif ($variable === 4) {
            // As you saw in the previous examples, you may pass an array of data to views:
            return \view('greetings', ['name' => 'Victoria']);
            // When passing information in this manner,
            // the data should be an array with key / value pairs.
            // Inside your view, you can then access each value using its corresponding key, such as <?php echo $key; /?/>.
        } elseif ($variable === 5) {

            // As an alternative to passing a complete array of data to the view helper function,
            // you may use the with method to add individual pieces of data to the view:
            return view('greeting')
                ->with('name', 'Victoria');
        } elseif ($variable === 6) {
        }

    }

    public function urlTest(Request $request)
    {
        $variable = '';
        if ($variable === 1) {
            $post = Post::find(1);
            echo url("/posts/{$post->id}");
            // http://example.com/posts/1
        } elseif ($variable === 2) {
            // If no path is provided to the url helper,
            // a Illuminate\Routing\UrlGenerator instance is returned,
            // allowing you to access information about the current URL:
            echo url()->current();
            echo url()->full();
            echo url()->previous();

            echo URL::current();
            echo URL::full();
            echo URL::previous(function () {

            });
        } elseif ($variable === 3) {
            $post = Post::find(1);
            echo route('post.show', ['post' => $post]);
        } elseif ($variable === 4) {
            echo route('comment.show', ['post' => 1, 'comment' => 3]);
            // http://example.com/post/1/comment/3

        } elseif ($variable === 5) {
            // Laravel allows you to easily create "signed" URLs to named routes.
            // These URLs have a "signature" hash appended to the query string which allows Laravel to
            // verify that the URL has not been modified since it was created.
            // Signed URLs are especially useful for routes that are publicly accessible yet need a layer of protection against URL manipulation.
            return URL::signedRoute('unsubscribe', ['user' => 1]);
        } elseif ($variable === 6) {
            // If you would like to generate a temporary signed route URL that expires, you may use the temporarySignedRoute method:
            return URL::temporarySignedRoute('unsubscribe', now()->addMinutes(30), ['user' => 1]);
        } elseif ($variable === 7) {
            // The action function generates a URL for the given controller action.
            // You do not need to pass the full namespace of the controller.
            // Instead, pass the controller class name relative to the App\Http\Controllers namespace:
            $url = action('HomeController@index');
        } elseif ($variable === 8) {
            // You may also reference actions with a "callable" array syntax:
            $url = action([UserController::class, 'index']);
        } elseif ($variable === 9) {
            // If the controller method accepts route parameters,
            // you may pass them as the second argument to the function:
            $url = action('HomeController@profile', ['id' => 1]);
        }

    }

    public function sessionTest(Request $request)
    {
        $var = '';
        if ($var === 1) {
            $session_one = $request->session()
                ->get(
                    'se_one',
                    'defaultVal'//This default value will be returned if the specified key does not exist in the session.
                );
        } elseif ($var === 2) {
            $session_two = $request->session()
                ->get(
                    'se_two',
                    function () {
                        //If you pass a Closure as the default value to the get method and the requested key does not exist,
                        //the Closure will be executed and its result returned:
                        return 'defaultVal_Two';
                    }
                );
        } elseif ($var === 3) {
            $data = $request->session()->all();
        } elseif ($var === 4) {
            $exists = $request->session()->has('key');
        } elseif ($var === 5) {
            // To store data in the session, you will typically use the put method or the session helper:
            $data = ['key_1' => 'val_1'];
            $request->session()->put($data);
            $request->session()->put('key_2', 'val_2');


            session($data);
        } elseif ($var === 6) {
            // The push method may be used to push a new value onto a session value that is an array.
            // For example, if the user.teams key contains an array of team names,
            // you may push a new value onto the array like so:
            $request->session()->push('user.teams', 'developers');
        } elseif ($var === 7) {
            // The pull method will retrieve and delete an item from the session in a single statement:
            $value = $request->session()->pull('key', 'default');
        } elseif ($var === 8) {
            // Sometimes you may wish to store items in the session only for the next request.
            // You may do so using the flash method.
            // Data stored in the session using this method will be available immediately and during the subsequent HTTP request.
            // After the subsequent HTTP request, the flashed data will be deleted.
            // Flash data is primarily useful for short-lived status messages:
            $request->session()->flash('status', 'Task was successful!');
        } elseif ($var === 9) {
            // If you need to keep your flash data around for several requests,
            // you may use the reflash method,
            // which will keep all of the flash data for an additional request.
            $request->session()->reflash();
            // If you only need to keep specific flash data, you may use the keep method:
            $request->session()->keep(['username', 'email']);
        } elseif ($var === 10) {
            // The forget method will remove a piece of data from the session.
            $request->session()->forget('key_1');
            // Forget multiple keys...
            $request->session()->forget(['key_1', 'key_2']);
            //  If you would like to remove all data from the session, you may use the flush method:
            $request->session()->flush();
        } elseif ($var === 11) {
            // Regenerating the session ID is often done in order to prevent malicious users
            // from exploiting a session fixation attack on your application.

            // Laravel automatically regenerates the session ID during authentication if you are using the built-in LoginController;
            // however, if you need to manually regenerate the session ID, you may use the regenerate method.

            $request->session()->regenerate();
        } elseif ($var === 12) {
            // To utilize session blocking, your application must be using a cache driver that supports atomic locks.
            // Currently, those cache drivers include the memcached, dynamodb, redis, and database drivers.
            // In addition, you may not use the cookie session driver.

            // 翻译：默认laravel是允许请求使用同一个session来同步执行的，比如你用js在执行两个HTTP请求的时候，假定此两个请求是同时执行的，对于很多应用
            // 这种情况很常见也不会有问题，但是session数据丢包在小部分情况下也是会存在的，比如正在对应用的两个不同的endpoints执行请求，那么就会执行两个
            // session写操作。

            // To mitigate this, Laravel provides functionality that allows you to limit concurrent requests for a given session.
            // To get started, you may simply chain the block method onto your route definition.
            // In this example, an incoming request to the /profile endpoint would acquire a session lock.
            // While this lock is being held,
            // any incoming requests to the /profile or /order endpoints which share the same session ID
            // will wait for the first request to finish executing before continuing their execution:


        }

    }

    public function validationTest(Request $request)
    {

    }

    /**
     * Store a secret message for the user.
     * @param Request $request
     * @param $id
     */
    public function encryptionTest(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->fill(
            [
                'secret' => Crypt::encryptString($request->secret),
            ]
        )->save();
    }

    /**
     * @param $encryptedValue
     */
    public function decryptValue($encryptedValue)
    {
        // If the value can not be properly decrypted,
        // such as when the MAC is invalid,
        // an Illuminate\Contracts\Encryption\DecryptException will be thrown:

        try {
            $decrypted = Crypt::decryptString($encryptedValue);
        } catch (DecryptException $e) {
            //

        }
    }

    /**
     * Update the password for the user.
     * @param Request $request
     */
    public function hashingUsage(Request $request)
    {
        $request->user()->fill(
            [
                'password' => Hash::make($request->newPassword,
                    // Adjusting The Bcrypt Work Factor
                    // If you are using the Bcrypt algorithm, the make method allows you
                    // to manage the work factor of the algorithm using the rounds option;
                    // however, the default is acceptable for most applications:
                    [
                        'rounds' => 12,
                    ]
                ),
            ]
        )->save();

        // Adjusting The Argon2 Work Factor
        // If you are using the Argon2 algorithm,
        // the make method allows you to manage the work factor of the algorithm
        // using the memory, time, and threads options; however,
        // the defaults are acceptable for most applications:

        $hashed = Hash::make('password', [
            'memory' => 1024,
            'time' => 2,
            'threads' => 2,
        ]);

        // The check method allows you to verify that a given plain-text string corresponds to a given hash.
        // However, if you are using the LoginController included with Laravel,
        // you will probably not need to use this directly,
        // as this controller automatically calls this method:

        if (Hash::check('plain-text', $hashed)) {
            // The passwords match...
        }

        // The needsRehash function allows you to determine if the work factor
        // used by the hasher has changed since the password was hashed:

        if (Hash::needsRehash($hashed)) {
            $hashed = Hash::make('plain-text');
        }

    }

    public function cacheFacadeTest()
    {
        //取值
        $value = Cache::get('key');

        //指定驱动
        $value = Cache::store('file')->get('foo');

        Cache::store('redis')->put('bar', 'baz', 600);// 10 minutes

        //默认值
        Cache::get('key', 'default');

        //闭包默认值
        Cache::get('key', function () {
            return DB::table('users')->get('id');
        });


        //判断有无
        if (Cache::has('key')) {

        }

        //增加integer类型的
        Cache::increment('key');
        $amount = 10;
        //步长
        Cache::increment('key', $amount);

        //减少integer类型的
        Cache::decrement('key');
        Cache::decrement('key', $amount);

        $seconds = 600;//10 minutes

        //取值，没有就用参数中的第三个闭包函数存到cache再取出
        Cache::remember('users',
            $seconds,//存储时间
            //如果读取cache没有值，就会执行这个闭包，然后返回的值会存储到cache中并返回给Cache Remember方法的调用方
            function () {
                return DB::table('users')->get();
            });

        //同上，永久存储
        Cache::rememberForever('users', function () {
            return DB::table('users')->get();
        });

        //取出并删除 ，如果不存在会返回null
        $value = Cache::pull('key');

        //存值
        Cache::put(
            'key',
            'value',
            $seconds  //如果不传，就是默认永久存储
        );

        Cache::put(
            'key',
            'value',
            now()->addMinutes(10)//或者传入DateTime实例设置过期时间
        );


        if (Cache::add('key', 'value', $seconds)) {
            //Cache中不存在该item，添加成功
        } else {
            //Cache中已经存在对应item，添加失败
        }

        Cache::forever('key', 'value');//永久存储到cache中
        //对于永久存储的，如果需要移除，请使用forget方法
        //如果使用的时Memcached驱动，当cache存储的值超过了最大可存量【溢出】，forever存储的值也可能被移除掉。
        Cache::forget('key');


        //其他移除方式 给一个0或者负值到第三个参数
        Cache::put('key', 'value', 0);
        Cache::put('key', 'value', -5);


        //Cache 清理，一次删除存储在Cache中的所有值
        Cache::flush(); //使用的时候请三思，


        /** Helper function 部分 */
        \cache('key');
        \cache(['key' => 'value'], $seconds);

        \cache(['key' => 'value'], now()->addMinutes(10));


        \cache()->remember('users', $seconds, function () {
            return DB::table('users')->get();
        });

        /** Cache Tags 部分 */

        $john = 'This is John';
        // 通过tags方法读取tag过的Cache，并将John存到这个tag类的Cache中
        Cache::tags(['people', 'artists'])->put('John', $john, $seconds);

        $anne = "This is Anne";
        Cache::tags(['people', 'authors'])->put('Anne', $anne, $seconds);

        //获取值
        $john = Cache::tags(['people', 'artists'])->get('John');

        $anne = Cache::tags(['people', 'authors'])->get('anne');


        //移除全部属于tag的Cache存储的值
        Cache::tags(['people', 'authors'])->flush(); //people tag及 authors tag的值全部移除，即anne john都被移除

        Cache::tags('authors')->flush(); //只移除了authors中的值，即anne


        /** 原子锁 */


        $lock = Cache::lock('foo', 10);

        if ($lock->get()) {
            // lock acquire for 10s ..锁定10秒
            //
            $lock->release();//释放
        }


        Cache::lock('foo')->get(function () {
            //锁定

            //闭包执行完成自动释放锁
        });

        //如果等待进入锁 等得太久，可以考虑设置一个最大等待时间
        //获取锁超时的话，会抛出 Illuminate\Contracts\Cache\LockTimeoutException 异常

        $lock = Cache::lock('foo', 10);

        try {
            $lock->block(5);

            //获取锁等待5秒之后的操作逻辑...


        } catch (LockTimeoutException $e) {
            //获取锁超时失败
        } finally {
            optional($lock)->release();
        }


        Cache::lock('foo', 10)->block(5, function () {
            //获取锁5秒之后的操作逻辑...
        });

        //跨进程管理原子锁，比如http请求的时候加锁，但是解锁是放在queue job或者某个事件触发逻辑之后。


        $id = 1;
        $podcast = Podcast::find($id);

        $lock = Cache::lock('foo', 120);

        if ($result = $lock->get()) {
            PodcastProcessed::dispatch($podcast, $lock->owner());
        }

        //无视锁的owner，强制解锁
        Cache::lock('foo')->forceRelease();


    }

    public function collectionTest()
    {

        //toUpper宏已经写在AppServiceProvider中了。

        $collection = collect(['first', 'seconde']);

        $upper = $collection->toUpper();// ['FIRST', 'SECOND']


        /** Lazy Collection */
        $lazyCollection = LazyCollection::make(function () {
            yield 1;
            yield 2;
            yield 3;
        });

        $lazyCollection->collect();

        get_class($collection);
        // 'Illuminate\Support\Collection'
        $collection->all();
        // [1,2,3]


        /** Eloquent Model LazyCollection */
        $users = \App\Models\User::cursor()->filter(function ($user) {
            return $user->id > 500;
        });

        foreach ($users as $user) {
            echo $user->id;
        }


        /** Create LazyCollection from file*/
        $fDatas = LazyCollection::make(function () {
            $handle = fopen('log.txt', 'r');
            while (($line = fgets($handle)) !== false) {
                yield $line;
            }
        });

        foreach ($fDatas as $fData) {
            echo $fData;
        }
    }
}
