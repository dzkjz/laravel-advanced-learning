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

    /**
     * 一般发送Notification的时候，会检查模型类里面的email属性值
     * 不过也可以使用本方法自定义，其返回值就是邮箱地址，或者一个邮箱地址做key=>名称做value的数组
     * @param $notification
     * @return array|mixed
     */
    public function routeNotificationForMail($notification)
    {
        return $this->email_address;
        return [$this->email_address => $this->name];
    }

    /**
     * If you would like to customize which channels a notifiable entity receives its broadcast notifications on,
     * you may define a receivesBroadcastNotificationsOn method on the notifiable entity:
     * @return string
     */
    public function receivesBroadcastNotificationsOn()
    {
        return 'users' . $this->id;
    }

    /**
     * To route Nexmo notifications to the proper phone number,
     * define a routeNotificationForNexmo method on your notifiable entity:
     * @param $notification
     * @return mixed
     */
    public function routeNotificationForNexmo($notification)
    {
        return $this->phone_number;
    }

    /**
     * Like routing SMS Notifications,
     * you should implement the routeNotificationForShortcode method on your notifiable model.
     * @param $notification
     */
    public function routeNotificationForShortcode($notification)
    {
        return $this->phone_number;
    }


}
