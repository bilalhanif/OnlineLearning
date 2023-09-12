<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AchievementUnlocked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $name;
    public $user;

    /**
     * Create a new event instance.
     */
    public function __construct($name, User $user)
    {
        $this->name = $name;
        $this->user = $user;
    }
}
