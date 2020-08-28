<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Event subscribers are classes that may subscribe to multiple events from within the class itself,
 * allowing you to define several event handlers within a single class.
 * Subscribers should define a subscribe method, which will be passed an event dispatcher instance.
 * You may call the listen method on the given dispatcher to register event listeners:
 *
 * Class UserEventSubscriber
 * @package App\Listeners
 */
class UserEventSubscriber
{
    /**
     * Handle user login events.
     */
    public function handleUserLogin($event)
    {

    }

    /**
     * Handle user logout events.
     */
    public function handleUserLogout($event)
    {

    }


    /**
     * @param \Illuminate\Events\Dispatcher $events
     * @return void|array
     */
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Login',
            [UserEventSubscriber::class, 'handleUserLogin']
        );

        $events->listen(
            'Illuminate\Auth\Events\Logout',
            [UserEventSubscriber::class, 'handleUserLogout']
        );

        // Alternatively, your subscriber's subscribe method may return an array of event to handler mappings.
        // In this case, the event listener mappings will be registered for you automatically:

        return [
            Login::class => [UserEventSubscriber::class, 'handleUserLogin'],
            Logout::class => [UserEventSubscriber::class, 'handleUserLogout']
        ];
    }

}
