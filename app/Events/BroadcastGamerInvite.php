<?php

namespace App\Events;

use App\Models\Game;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BroadcastGamerInvite implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $invite_user;
    private $slug;
    private $user_name;

    /**
     * Create a new event instance.
     */
    public function __construct(string $invite_user, string $user_name, string $slug)
    {
        $this->invite_user = $invite_user;
        $this->slug = $slug;
        $this->user_name = $user_name;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        \Log::info("games.invite.{$this->invite_user}");
        return [
            new PrivateChannel("games.invite.{$this->invite_user}"),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            "invite_from" => $this->user_name,
            "game_slug" => $this->slug
        ];
    }
}