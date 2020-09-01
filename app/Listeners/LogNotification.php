<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Queue\InteractsWithQueue;

class LogNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param NotificationSent $event
     * @return void
     */
    public function handle(NotificationSent $event)
    {
        // Within an event listener, you may access the notifiable, notification, and channel properties
        // on the event to learn more about the notification recipient or the notification itself:


        // $event->channel
        // $event->notifiable
        // $event->notification
        // $event->response
    }
}
