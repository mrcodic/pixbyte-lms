<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RealTimeMessage implements ShouldBroadcast
{
    use SerializesModels, InteractsWithSockets,Dispatchable;

    public string $message;

    public function __construct(string $message)
    {
//        dd(1);
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('events');
    }
    public function broadcastAs()
    {
        return 'task_created';
    }
}
