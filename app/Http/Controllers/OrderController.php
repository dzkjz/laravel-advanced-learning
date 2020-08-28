<?php

namespace App\Http\Controllers;

use App\Events\OrderShipped;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{


    public function ship($orderId)
    {
        $order = Order::findOrFail($orderId);

        // Order shipment logic...

        // To dispatch an event, you may pass an instance of the event to the event helper.
        // The helper will dispatch the event to all of its registered listeners.
        // Since the event helper is globally available, you may call it from anywhere in your application:
        event(new OrderShipped($order));

        // Alternatively, if your event uses the Illuminate\Foundation\Events\Dispatchable trait,
        // you may call the static dispatch method on the event.
        // Any arguments passed to the dispatch method will be passed to the event's constructor:
        OrderShipped::dispatch($order);

        // When testing,
        // it can be helpful to assert that certain events were dispatched without actually triggering their listeners.
        // Laravel's [built-in testing helpers](https://laravel.com/docs/master/mocking#event-fake) makes it a cinch.
    }


}
