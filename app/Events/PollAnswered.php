<?php

namespace App\Events;

use App\Models\PollAnswer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PollAnswered implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pollAnswer;

    /**
     * Create a new event instance.
     */
    public function __construct(PollAnswer $pollAnswer)
    {
        $this->pollAnswer = $pollAnswer;
    }

    public function broadcastWith(){
        return [
            'hello' => 'there',
            'pollAnswer' => $this->pollAnswer,
        ];
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new  Channel('channel'),
        ];
    }
}
