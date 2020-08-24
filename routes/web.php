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

