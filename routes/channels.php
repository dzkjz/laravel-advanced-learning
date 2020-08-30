<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});

//Broadcast::channel('order.{orderId}', function ($user, $orderId) {
//    return $user->id === \App\Models\Order::findOrNew($orderId)->user_id;
//});
Broadcast::channel('order.{orderId}', function ($user, \App\Models\Order $order) {
    return $user->id === $order->user_id;
});

//默认情况下，信道里的用户授权是先用laravel应用的默认guard进行授权认证，然后再用Broadcast的channel方法
//执行信道鉴权。

//如果guard授权失败，那用户认证就是失败了就直接被认证拒绝，不会执行到后面的callback回调

//如果有需要添加或者使用其他guard配置，可以如下：

Broadcast::channel('channel', function () {
    // ...
}, ['guards' => ['web', 'admin']]);

Broadcast::channel('order.{orderId}', \App\Broadcasting\OrderChannel::class);

//直播间，聊天室信道
Broadcast::channel('chat.{roomId}', function ($user, $roomId) {
    if ($user->canJoinRoom($roomId)) {
        return ['id' => $user->id, 'name' => $user->name];
    }
});
