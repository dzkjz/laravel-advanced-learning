<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoicePaid extends Notification implements ShouldQueue
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
        //如果需要自己定义一个发送渠道，可以参考 https://laravel-notification-channels.com/
        return $notifiable->prefers_sms ? ['nexmo'] : ['mail', 'database'];
//        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Notification Subject') //默认邮件的主题就是notification类名，比如本类就是Invoice Paid，使用subject方法可以自定义标题
//            ->from('john@example.com','John') //如果有需要可以用这个设置，但是一般都是在mail.php中的from设置处配置好了的
            ->mailer('postmark') //默认发送邮件的驱动是mail.php中的默认驱动， 'default' => env('MAIL_MAILER', 'smtp'),不过可以使用这个mailer方法另行指定
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');

//        return (new MailMessage)
//            ->view(
//                [
//                    'emails.name.html',
//                    'emails.name.plain'//第二个数组成员，这个将指定为纯文本邮件view的模板
//                ],
//                [
//                    'invoice' => $this->invoice
//                ]
//            );


        //In addition, you may return a full mailable object from the toMail method:
//        return (new Mailable($this->invoice))->to($notifiable->email);


//        return (new MailMessage)
//            ->error() //一般比如是失败消息就加个这个，而且使用了这个的邮件的按钮也会是红色而不是原本的蓝色
//            ->subject('Notification Subject')
//            ->line('');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
        //本方法也会被broadcast广播的时候调用，返回值就会被广播到js前端应用中，
        //如果需要存到database和广播出去的数据表示不同，那就把需要存到database中的
        //写到toDatabase方法中去
    {
        //返回的数组，会encode为json然后存储于notifications表的data数据列
        return [
            'invoice_id' => $this->invoice->id,
            'amount' => $this->invoice->amount,
        ];
    }

    /*
     * Determine which queues should be used for each notification channel.
     * */
    public function viaQueues()
    {
        //为什么定义的是两个，因为 via方法内部，可以返回['mail','slack']值，
        //所以是可以针对两个渠道分别指定queue
        return [
            'mail' => 'mail-queue',
            'slack' => 'slack-queue',
        ];
    }

    /**
     * Get the voice representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return VoiceMessage
     */
    public function toVoice($notifiable)
    {
        //...
    }

}
