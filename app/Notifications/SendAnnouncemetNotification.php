<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class SendAnnouncemetNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $notification ,$announcement_id;

    public function __construct($notification,$announcement_id)
    {
        $this->notification = (object)$notification;
        $this->announcement_id = $announcement_id;
        Log::info('in announcement');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast','database'];
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
            return [
                'data' => $this->notification,
                'key' => $this->notification->key,
                'case' => $this->notification->case,
                'concatenation' => json_encode($this->notification->concatenation),
                'redirect_route' => $this->notification->redirect_route,
                'unread' =>  true,
                'text' => $this->notification->text
        ];
    }
    public function send($notifiable, Notification $notification)
    {
        $data = $notification->toDatabase($notifiable);

        // set custom message in another variable and unset it from default array.

        // lets create a DB row now with our custom field message text

        return $notifiable->routeNotificationFor('database')->create([

            'id' => $notification->id,
            'announcement_id' => $this->announcement_id,
            'notifiable_type'=> \Auth::user()->id,
            'type' => get_class($notification),
            'data' => $this->notification,
            'read_at' => null,
        ]);
    }
}
