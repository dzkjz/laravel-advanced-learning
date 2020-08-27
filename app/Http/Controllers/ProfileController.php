<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Update the user's profile.
     * @param Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        //$request->user(); returns an instance of the authenticated user...
    }

    public function authenticationTest()
    {
        if (Auth::check()) {

            // The user is logged in ...

            // Even though it is possible to determine if a user is authenticated using the check method,
            // you will typically use a middleware to verify that the user is
            // authenticated before allowing the user access to certain routes / controllers.
            // To learn more about this, check out the documentation on https://laravel.com/docs/master/authentication#protecting-routes

        }
    }

    public function authenticateSessionTest($password)
    {
        // This method requires the user to provide their current password,
        // which your application should accept through an input form:
        Auth::logoutOtherDevices($password);
        // When the logoutOtherDevices method is invoked,
        // the user's other sessions will be invalidated entirely,
        // meaning they will be "logged out" of all guards they were previously authenticated by.

        // When using the AuthenticateSession middleware in combination with a custom route name for the login route,
        // you must override the unauthenticated method on your application's exception handler
        // to properly redirect users to your login page.
    }
}
