<?php

namespace App\Events;

use App\Models\Message;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;

use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message->load([
            'user',
            'attachments'
        ]);
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('chat.' . $this->message->channel_id),
        ];
    }

  public function broadcastAs()
{
    return 'MessageSent';
}

public function broadcastWith()
{
    return [
        'message' => [
            'id' => $this->message->id,
            'content' => $this->message->content,
            'reference_date' => $this->message->reference_date,
            'created_at' => $this->message->created_at,
        ],

        'user' => [
            'id' => $this->message->user->id,
            'name' => $this->message->user->name,
            'photo' => $this->message->user->photo
                ? asset('storage/' . $this->message->user->photo)
                : 'https://ui-avatars.com/api/?name=' . urlencode($this->message->user->name),
        ],

        'channel_id' => $this->message->channel_id,
    ];
}
    
    
}