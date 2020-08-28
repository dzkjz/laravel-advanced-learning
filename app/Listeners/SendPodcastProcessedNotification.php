<?php


namespace App\Listeners;


use App\Events\PodcastProcessed;

class SendPodcastProcessedNotification
{
    /**
     * SendPodcastProcessedNotification constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param PodcastProcessed $event
     */
    public function handle(PodcastProcessed $event)
    {

    }
}
