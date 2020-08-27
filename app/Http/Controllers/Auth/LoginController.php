<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * If you need more robust customization of the response returned when a user is authenticated,
     * Laravel provides an empty authenticated(Request $request, $user) method within the AuthenticatesUsers trait.
     */
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Therefore, you can define your own authenticated method within the LoginController class:
     * @param Request $request
     * @param $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function authenticated(Request $request, $user)
    {
        return response(
            []
        );
    }

    /**
     * By default, Laravel uses the email field for authentication.
     * If you would like to customize this, you may define a username method on your LoginController:
     */
    public function username()
    {
        return 'username';
    }

    /**
     *  You may also customize the "guard" that is used to authenticate and register users.
     * To get started, define a guard method on your LoginController, RegisterController, and ResetPasswordController.
     * The method should return a guard instance:
     */
    public function guard()
    {
        return Auth::guard('guard-name');
    }

    public function retrieveUserTest()
    {
        // Get the currently authenticated user...
        $user = Auth::user();

        // Get the currently authenticated user's ID...
        $id = Auth::id();
    }
}
