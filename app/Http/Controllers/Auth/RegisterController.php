<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use \App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * To modify the form fields that are required when a new user registers with your application,
 * or to customize how new users are stored into your database,
 * you may modify the RegisterController class.
 * This class is responsible for validating and creating new users of your application.
 * Class RegisterController
 *
 * @package App\Http\Controllers\Auth\
 */
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     * The validator method of the RegisterController contains the validation rules for new users of the application.
     * You are free to modify this method as you wish.
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     * The create method of the RegisterController is responsible for creating new App\Models\User records in your
     * database using the Eloquent ORM. You are free to modify this method according to the needs of your database.
     * @param array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
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
}
