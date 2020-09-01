<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail, HasLocalePreference
{

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Send the password reset notification.
     *
     * You may easily modify the notification class used to send the password reset link to the user.
     * To get started, override the sendPasswordResetNotification method on your User model.
     *
     * @param string $token The password reset $token is the first argument received by the method:
     */
    public function sendPasswordResetNotification($token)
    {
//        Within this method, you may send the notification using any notification class you choose.
        $this->notify(new ResetPasswordNotification($token));
    }

    /***
     * Get the user's preferred locale.
     * @return mixed|string|null
     */
    public function preferredLocale()
    {
        return $this->locale;
    }
}
