<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoicesPaid extends Notification
{
    use Queueable;

    /**
     * @var Invoice
     */
    protected $invoice;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)->markdown('mail.invoice.paid');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    /**
     * The broadcast channel broadcasts notifications using Laravel's event broadcasting services,
     * allowing your JavaScript client to catch notifications in realtime.
     * If a notification supports broadcasting,
     * you can define a toBroadcast method on the notification class.
     * This method will receive a $notifiable entity and should return a BroadcastMessage instance.
     * If the toBroadcast method does not exist,
     * the toArray method will be used to gather the data that should be broadcast.
     * The returned data will be encoded as JSON and broadcast to your JavaScript client.
     * Let's take a look at an example toBroadcast method:
     */
    public function toBroadcast($notifiable)
    {
        return (new BroadcastMessage(
            [
                'invoice_id' => $this->invoice->id,
                'amount' => $this->invoice->amount,
            ]
        ))
            // All broadcast notifications are queued for broadcasting.
            // If you would like to configure the queue connection or
            // queue name that is used to queue the broadcast operation,
            // you may use the onConnection and onQueue methods of the BroadcastMessage:
            ->onConnection('sqs')
            ->onQueue('broadcasts');

    }

    /**
     * In addition to the data you specify,
     * all broadcast notifications also have a type field containing the full class name of the notification.
     * If you would like to customize the notification type that is provided to your JavaScript client,
     * you may define a broadcastType method on the notification class:
     * @return string
     */
    public function broadcastType()
    {
        return 'broadcast.message';
    }

    /**
     * If a notification supports being sent as an SMS,
     * you should define a toNexmo method on the notification class.
     * This method will receive a $notifiable entity and
     * should return a Illuminate\Notifications\Messages\NexmoMessage instance:
     */
    public function toNexmo($notifiable)
    {
        return (new NexmoMessage)
            ->content('Your SMS message content');
    }

    public function toShortcode($notifiable)
    {
        return [
            'type' => 'alert',
            'custom' => [
                'code' => 'ABC123',
            ]
        ];
    }
}
