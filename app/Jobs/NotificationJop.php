<?php

namespace App\Jobs;

use App\Http\Traits\SendNotificationTrait;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotificationJop implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,SendNotificationTrait;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $user , $text,$redirect,$classroom,$room,$textPhone,$type;
    public function __construct($classroom,$room,$text,$user,$redirect,$textPhone,$type)
    {
        $this->classroom=$classroom;
        $this->room=$room;
        $this->text=$text;
        $this->user=$user;
        $this->redirect=$redirect;
        $this->textPhone=$textPhone;
        $this->type=$type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->type=='4'){
            $name=$this->room? $this->room?->name:"";
        }elseif($this->type=='1' || $this->type=='2'){
            $name=$this->room ? $this->room?->name:"";
        }
        else{
            $name=$this->room? $this->room?->title:"";
        }

        $arr=[];
        $arr[]=$this->user;

                $this->sendSingleUserNotificationInQueue(
                     $this->text,
                    $arr,
                    $this->classroom,
                     $this->type,
                     [],
                     $this->redirect,
                     false,
                     false,
                     ['notification_only'],
                     $this->textPhone,
                     $name??''
                 );
                 $this->send_fcm_notification($this->user,$this->textPhone,$this->type,$name);
    }

    function send_fcm_notification($user, $text, $type , $name )
    {
       Log::info('start send fcm');
       $firebaseTokens = [];
       $firebaseTokens = $user->device_tokens->pluck('device_token')->toArray();
       Log::info('firebase: ' . implode(',', $firebaseTokens));

       $dataNotification = [
           "text" => $text ?? '',
           'date' => Carbon::now()->diffForHumans(),
           'read' => false,
           'type' => $type,
           "name" => $name ?? '',
           'title' => $type == '4' ? "Announcement 📣" : $name,
           'body' => $text ?? '',
       ];

       $headers = [
           'Authorization: key=' . config('app.fcm_server_key'),
           'Content-Type: application/json',
       ];

       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
       curl_setopt($ch, CURLOPT_POST, true);
       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

       foreach ($firebaseTokens as $token) {
           $data = [
               "to" => $token,
               "data" => $dataNotification,
               "notification" => $dataNotification,
           ];

           $dataString = json_encode($data);
           Log::info('notify data:', ['dataString' => $dataString]);

           curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
           $response = curl_exec($ch);
           // Handle the response as needed
       }

       curl_close($ch);



    }

}

