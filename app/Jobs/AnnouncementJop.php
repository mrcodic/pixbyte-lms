<?php

namespace App\Jobs;

use App\Events\AnnouncementEvent;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AnnouncementJop implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $users,$announcement,$instructor_id;
    public function __construct($users,$announcement,$instructor_id)
    {
       $this->users=$users;
       $this->announcement=$announcement;
       $this->instructor_id=$instructor_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->users as $user){
           event(new AnnouncementEvent($user,$this->announcement,$this->instructor_id));

           $user=User::find($user);
           $text= $this->announcement->desc;
           $textPhone=$text;
           \App\Jobs\NotificationJop::dispatch($this->announcement,$this->announcement,$text,$user,$redirect=null,$textPhone,'4');

           \App\Jobs\NotificationJop::dispatch($this->announcement,$this->announcement,$text,$user->parent,$redirect=null,$textPhone,'4');//instructor


        }
    }
}
