<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast as BroadcastingShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessage implements BroadcastingShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $chatId;

    public function __construct(Message $message)
    {
        $this->message = $message;
        $this->chatId = $message->chat_id;
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('chat.' . $this->chatId);
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->message->load('user')  // load the user data to include it in the broadcast
        ];
    }
}
