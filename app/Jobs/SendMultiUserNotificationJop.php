<?php

namespace App\Jobs;

use App\Http\Traits\SendNotificationTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendMultiUserNotificationJop implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,SendNotificationTrait;
 protected $text,$targetUsers, $notification, $fromUserEmail, $fromUserName, $sendVia,$textPhone,$room_name;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($text,$targetUsers, $notification, $fromUserEmail, $fromUserName, $sendVia,$textPhone,$room_name)
    {
        $this->text = $text;
        $this->targetUsers = $targetUsers;
        $this->notification = $notification;
        $this->fromUserEmail = $fromUserEmail;
        $this->fromUserName = $fromUserName;
        $this->sendVia = $sendVia;
        $this->textPhone=$textPhone;
        $this->room_name=$room_name;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('start SendMultiUserNotificationsJob  queue');
//           dd($this->text,$this->targetUsers, $this->notification, $this->fromUserEmail,
//               $this->fromUserName, $this->sendVia);
        foreach ($this->targetUsers as $user){
            $this->notification['concatenation']=$user->email;

            $this->sendSingleUserNotification($this->text,$user, $this->notification, $this->fromUserEmail,
                $this->fromUserName, $this->sendVia,$this->textPhone,$this->room_name);
        }

    }
}
