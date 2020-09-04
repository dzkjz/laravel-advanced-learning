<?php

namespace App\Models;

use App\Casts\Address;
use App\Casts\Hash;
use App\Events\UserDeleted;
use App\Events\UserSaved;
use App\Json;
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
     * 返回的模型实例属性值，这属性里面的attribute都会被隐藏掉
     * 如果需要隐藏的时候模型关联，请使用模型关联的方法的名字
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * 与上面hidden属性相对，这里的就是需要展示到返回出去的json值的白名单，未填入的，自动设置为hidden
     * @var string[]
     */
    protected $visible = [
        'first_name',
        'last_name',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'address' => Address::class,
        //对于可以传参数的cast，可以使用:字符，多个的可以用逗号
        'secret' => Hash::class . ':sha256',//参数会传入Hash cast的构造函数
        //也可以指定cast为 一个实现了 castable 接口的类
        'families' => Json::class . ':argument',//当然可以用参数，会直接传给castUsing方法里面的那个类的构造函数，这里【Json没有构造函数】只是做个示例，
        'options' => 'array',//值存储会序列化为json格式的，使用array cast会在取值时，json转为array，存值时，array转为json
        'created_at' => 'datetime:Y-m-d',//使用datetime cast的可以指定格式，当序列化为json或者array的时候，很有用
    ];

    /**可以指定事件触发
     * @var array
     */
    protected $dispatchesEvents = [
        'saved' => UserSaved::class,
        'deleted' => UserDeleted::class,
        //定义了指向触发，就可以写监听器，handle这些事件了
    ];

    /**
     * user模型实例数据在返回出去被序列化为json格式后，如果需要追加值，可以先添加一个存取器【getIsAdminAttribute】计算出结果，然后
     * 在$appends属性里面添加，这样就会在返回出去的json值里看到结果了，
     * hidden visible属性对这里面的值同意有限定作用
     * @var string[]
     */
    protected $appends = ['is_admin'];

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


    /** 获取名字
     * @param $value
     * @return string
     */
    public function getFirstNameAttribute($value)
    {
        return ucfirst($value);//字符串首字母大写
    }

    public function getLastNameAttribute($value)
    {
        return ucfirst($value);
    }

    /** 返回计算值
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**设置 【设置值会最后存储到数据库中】就是lowercase的
     * @param $value
     */
    public function setFirstNameAttribute($value)
    {
        // The mutator will receive the value that is being set on the attribute,
        // allowing you to manipulate the value and
        // set the manipulated value on the Eloquent model's internal $attributes property.
        $this->attributes['first_name'] = strtolower($value);
    }

    public function getIsAdminAttribute()
    {
        return $this->attributes['admin'] === 'yes';
    }

    /**
     * 自定义序列化的日期格式【不建议用这个方法】
     *
     * 如果所有的都一个格式，可以在$dateFormat属性里面配置
     * 单独的可以在casts属性里面配置
     *
     * @param \DateTimeInterface $dateTime
     * @return string
     */
    public function serializeDate(\DateTimeInterface $dateTime)
    {
        return $dateTime->format('Y-m-d');
        //Customizing The Date Format Per Attribute
        //You may customize the serialization format of individual
        // Eloquent date attributes by specifying the date format in the cast declaration:
        //
        //protected $casts = [
        //    'birthday' => 'date:Y-m-d',
        //    'joined_at' => 'datetime:Y-m-d H:00',
        //];
    }


}
