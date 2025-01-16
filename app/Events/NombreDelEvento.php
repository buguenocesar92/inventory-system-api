<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class NombreDelEvento implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public string $message;
    public int $userId;

    public function __construct(string $message, int $userId)
    {
        $this->message = $message;
        $this->userId = $userId;
    }

    public function broadcastOn()
    {
        return new Channel('nombre-del-canal');
    }

    public function broadcastAs()
    {
        return 'NombreDelEvento';
    }
}
