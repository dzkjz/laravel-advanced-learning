<?php

namespace App\Http\Controllers;

use App\Contract\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\View;

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
}
