<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TestSoketi implements ShouldBroadcast
{
    public $message;

    public function __construct($message = "hello websocket")
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('test-channel');
    }
}
