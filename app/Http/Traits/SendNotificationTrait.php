<?php
namespace App\Http\Traits;
use App\Jobs\SendMultiUserNotificationJop;
use App\Models\User;
use App\Notifications\SendOnlySystemNotification;

trait SendNotificationTrait
{

    private function sendSingleUserNotification(
        $text,
        $targetUser,
        array $notification,
        bool $fromUserEmail = false,
        bool $fromUserName = false,
        array $sendVia = [],
        $textPhone,
    ): void {
            $notification = $this->handleNotificationArray($text,$targetUser, $notification, $fromUserEmail, $fromUserName,$textPhone);
            $this->checkSendEmail($targetUser, $notification, $sendVia);


    }
    private function sendSingleUserNotificationInQueue(
        string $text = null,
               $targetUsers,
        string $notificationKey,//model
        string $notificationCase,// action
        array $concatenation = [],//اميل اليوزر ال رايحله
        string $redirectRoute = null,
        bool $fromUserEmail = false,
        bool $fromUserName = false,
        array $sendVia = ['email'],
       string $textPhone,
       string $room_name,


    ) {

        $when = now()->addSeconds(10);

        $notification = $this->notification($text,$notificationKey, $notificationCase, $concatenation, $redirectRoute,$textPhone,$room_name);
        return SendMultiUserNotificationJop::dispatch($text,$targetUsers, $notification, $fromUserEmail, $fromUserName, $sendVia,$textPhone,$room_name);
    }

    public function notification($text,$notificationKey, $notificationCase, $concatenation, $redirectRoute,$textPhone,$room_name): array
    {
        $notification = [];
        $notification['text'] = $text;
        $notification['key'] = $notificationKey;
        $notification['case'] = $notificationCase;
        $notification['concatenation'] = $concatenation;
        $notification['redirect_route'] = $redirectRoute ?? null;
        $notification['textPhone']=$textPhone;
        $notification['room_name']=$room_name;
        return $notification;
    }
    private function checkSendEmail( $targetUser, array $notification, array $sendVia)
    {
//        $when = now()->addSeconds(10);
        if (in_array('notification_only', $sendVia)) {
            $targetUser->notify((new SendOnlySystemNotification($notification)));
        }
    }
    private function handleNotificationArray(
        $text,
         $targetUser,
        array $notification,
        bool $fromUserEmail = false,
        bool $fromUserName = false
    ) {
        $notification['username'] = $targetUser['name'];
        $notification['text'] = $text;
        $notification['email'] = $targetUser['email'];

        if (!in_array('from_email', $notification)) {
            $notification['from_email'] = "test@gmail.com";
            $notification['from_name'] = 'Mives';
        }

        $notification['salutation'] = null;
        return $notification;
    }
}
