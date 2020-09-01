<?php


namespace App\Channels;


use Illuminate\Notifications\Notification;

class VoiceChannel
{

    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toVoice($notifiable);
        // Send notification to the $notifiable instance...
    }
}
