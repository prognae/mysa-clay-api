<?php

namespace App\Events;

use App\Helpers\Cryptor;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TestEvent implements ShouldBroadcast, ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId, $message;

    /**
     * Create a new event instance.
     */
    public function __construct($user, $message)
    {
        $this->userId = $user->id;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    // public function broadcastOn(): array
    // {
    //     return [
    //         new PrivateChannel('channel-name'),
    //     ];
    // }
    
    // public function broadcastOn(): Channel
    // {
    //     return new PrivateChannel('test-channel.' . $this->user->id);
    // }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('test-channel.' . Cryptor::encrypt($this->userId));
    }
}
