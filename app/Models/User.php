<?php

namespace App\Models;

use App\Events\UserDeleted;
use App\Events\UserSaved;
use App\Notifications\ResetPasswordNotification;
use App\Scopes\AgeScope;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Builder;
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

    /**可以指定事件触发
     * @var array
     */
    protected $dispatchesEvents = [
        'saved' => UserSaved::class,
        'deleted' => UserDeleted::class,
        //定义了指向触发，就可以写监听器，handle这些事件了
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

    /**
     * To assign a global scope to a model,
     * you should override a given model's booted method
     */
    public static function booted()
    {
        // and use the addGlobalScope method:
        static::addGlobalScope(new AgeScope);
        // After adding the scope, a query to User::all() will produce the following SQL:
        // select * from `users` where `age` > 200

        //闭包全局作用域
        static::addGlobalScope('age', function (Builder $builder) {
            $builder->where('age', '>', 200);
        });

        // Instead of using custom event classes,
        // you may register Closures that execute when various model events are fired.
        // 使用闭包，在created事件触发时，执行闭包调用
        static::created(function ($user) {
            //
        });
    }

    public function scopePopular($query)
    {
        return $query->where('votes', '>', 100);
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    /**动态作用域
     * Sometimes you may wish to define a scope that accepts parameters.
     * To get started, just add your additional parameters to your scope.
     * Scope parameters should be defined after the $query parameter:
     * @param $query
     * @param $type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

}
