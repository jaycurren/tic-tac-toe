<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BroadcastGamerAccept implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $invited_user;

    /**
     * Create a new event instance.
     */
    public function __construct(string $invited_user)
    {
        $this->invited_user = $invited_user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel("games.accept.{$this->invited_user}"),
        ];
    }
}