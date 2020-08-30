<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ShippingStatusUpdated implements
    ShouldBroadcast
//    , ShouldBroadcastNow //广播事件直接使用sync而不是默认的queue驱动
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * Information about the shipping status update.
     * @var string
     */
    public $update;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     * 本方法是实现ShouldBroadcast接口所必须，带有一个默认实现。
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //因为我们只需要创建订单的用户有这个权限查看更新【通过本事件更新出去】，所以创建一个私有信道并且关联该订单信息。
        return new PrivateChannel('order' . $this->update->order_id);
    }

    /**
     * By default, Laravel will broadcast the event using the event's class name.
     * However, you may customize the broadcast name by defining a broadcastAs method on the event:
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'server.created';
        // If you customize the broadcast name using the broadcastAs method,
        // you should make sure to register your listener with a leading . character.
        // This will instruct Echo to not prepend the application's namespace to the event:
        //
        //.listen('.server.created', function (e) {
        //    ....
        //});注意sever.created前有个.号
    }

    /**
     * However, if you wish to have more fine-grained control over your broadcast payload,
     * you may add a broadcastWith method to your event.
     * This method should return the array of data that you wish to broadcast as the event payload:
     * 用于高精度控制广播数据
     * Get the data to broadcast.
     * @return array
     */
    public function broadcastWith()
    {
        return ['id' => $this->user->id];
    }

    /**
     * 默认情况下，使用的是queue.php中配置的queue设置，
     * 如果需要自定义控制，可以设置本属性值。
     * @var string
     */
    public $broadcastQueue = 'your-queue-name';

    /**
     * 用于判断事件是否需要广播出去
     * 返回真的时候，事件将会广播出去
     * @return bool
     */
    public function broadcastWhen()
    {
        return $this->value > 100;
    }

}
