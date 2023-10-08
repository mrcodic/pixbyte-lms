<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnnouncementEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
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
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('App.Models.User.'.$this->users);
    }
    public function broadcastWith()
    {

        return [
            'data' => [
                'user_id'=>$this->users,
                'instructor'=>$this->instructor_id->name,
                'title'=>$this->announcement->name,
                'desc'=>$this->announcement->desc,
                'created_at'=>$this->announcement->created_at->format('Y-m-d'),
                'count'=>$this->announcement->count(),
            ],
        ];
    }
}
