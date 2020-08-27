<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/users', 'UserController@getIndex');
Route::get('/cacheTest', 'UserController@cacheTest');

//Route::resource('', function () {
//})->parameters([])->names([
//    'create' => 'photos.build'
//])->scoped(['post' => 'slug'])->shallow();

// The route helper may be used to generate URLs to named routes.
// Named routes allow you to generate URLs without being coupled to the actual URL defined on the route.
// Therefore, if the route's URL changes, no changes need to be made to your route function calls.
// For example, imagine your application contains a route defined like the following:
Route::get('/post/{post}', function () {
    //
})->name('post.show');
// To generate a URL to this route, you may use the route helper like so:
// echo route('post.show', ['post' => 1]);

Route::get('/post/{post}/comment/{comment}', function () {
    //
})->name('comment.show');

// To verify that an incoming request has a valid signature,
// you should call the hasValidSignature method on the incoming Request:
Route::get('/unsubscribe/{user}', function (\Illuminate\Http\Request $request) {
//    if (!$request->hasValidSignature()) {
//        abort(404);
//    }
    //
})->name('unsubscribe')
    // If it is not already present, you should assign this middleware a key in your HTTP kernel's routeMiddleware array:
    ->middleware('signed');

// For some applications, you may wish to specify request-wide default values for certain URL parameters.
// For example, imagine many of your routes define a {locale} parameter:
Route::get('/{locale}/posts', function () {
// It is cumbersome to always pass the locale every time you call the route helper.
// So, you may use the URL::defaults method to define a default value for this parameter
// that will always be applied during the current request.
})->name('post.index');

Route::get('home', function () {
    $value = session('key');

    $value = session('key', 'default');

    // Store a piece of data in the session
    session(['key_1' => $value]);


});

// To mitigate this, Laravel provides functionality that allows you to limit concurrent requests for a given session.
// To get started, you may simply chain the block method onto your route definition.
// In this example, an incoming request to the /profile endpoint would acquire a session lock.
// While this lock is being held,
// any incoming requests to the /profile or /order endpoints which share the same session ID will wait for the first request to finish executing before continuing their execution:
Route::post('/profile', function () {
    //
})->block($lockSeconds = 10, $waitSeconds = 10);

Route::post('/order', function () {
    //
})
    ->block(
    // The block method accepts two optional arguments.
    // The first argument accepted by the block method is the maximum number of seconds
    // the session lock should be held for before it is released.
    // Of course, if the request finishes executing before this time the lock will be released earlier.
        $lockSeconds = 10,
        // The second argument accepted by the block method is the number of seconds
        // a request should wait while attempting to obtain a session lock.
        // A Illuminate\Contracts\Cache\LockTimoutException will be thrown
        // if the request is unable to obtain a session lock within the given number of seconds.
        $waitSeconds = 10
    // If neither of these arguments are passed,
    // the lock will be obtained for a maximum of 10 seconds and requests
    // will wait a maximum of 10 seconds while attempting to obtain a lock:
    );

Route::get('/post/create', 'PostController@create');

Route::post('post', 'PostController@store');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


// Let's explore an example of using the can middleware to authorize that a user can update a blog post:

Route::put('/post/{post}', function (\App\Models\Post $post) {
    // The current user may update the post...
})
    ->middleware('can:update,post');
// In this example, we're passing the can middleware two arguments.
// The first [can] is the name  of the action we wish to authorize and
// the second [update,post] is the route parameter we wish to pass to the policy method.
// In this case, since we are using implicit model binding,
// a Post model will be passed to the policy method.
// If the user is not authorized to perform the given action,
// a HTTP response with a 403 status code will be generated by the middleware.


// Again, some actions like create may not require a model instance.
Route::post('/post', function () {

    // The current user may create posts...

})
    ->middleware('can:create,App\Models\Post');
// In these situations, you may pass a class name to the middleware.
// The class name will be used to determine which policy to use when authorizing the action:
