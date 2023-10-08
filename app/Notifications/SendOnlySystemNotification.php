<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class SendOnlySystemNotification extends Notification  implements ShouldBroadcast
{
    use Queueable;

    protected $notification;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($notification)
    {

        $this->notification = (object)$notification;
        Log::info('in SendOnlySystemNotification');
//        Log::info($this->notification.'555' );


    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['database'];
        // return ['broadcast','database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
//        Log::info('in.'.$this->notification);
        return [
            'data' => $this->notification,
            'key' => $this->notification->key,
            'case' => $this->notification->case,
            'concatenation' => json_encode($this->notification->concatenation),
            'redirect_route' => $this->notification->redirect_route,
            'unread' =>  true,
            'text' => $this->notification->text,
            "text_phone"=>$this->notification->textPhone,
            'room_name'=>$this->notification->room_name
        ];
    }

}
