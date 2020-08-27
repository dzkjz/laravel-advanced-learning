<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /** Get the broker to be used during password reset.
     *
     *
     *
     * In your auth.php configuration file, you may configure multiple password "brokers",
     * which may be used to reset passwords on multiple user tables.
     * You can customize the included ForgotPasswordController and ResetPasswordController
     * to use the broker of your choice by overriding the broker method:
     *
     *
     * @return mixed
     */
    public function broker()
    {
        return Password::broker('name');
    }
}
