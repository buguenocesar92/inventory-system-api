<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class NombreDelEvento implements ShouldBroadcast
{
    use SerializesModels;

    public string $message;
    public int $userId;

    public function __construct(string $message, int $userId)
    {
        $this->message = $message;
        $this->userId = $userId;
    }

    /**
     * Define el canal de transmisión como privado.
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->userId);
    }

    /**
     * Datos enviados al cliente.
     */
    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            'userId' => $this->userId,
        ];
    }
}
