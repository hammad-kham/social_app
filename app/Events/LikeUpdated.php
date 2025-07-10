<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class LikeUpdated implements ShouldBroadcastNow
{
    use SerializesModels;

    public $postId;
    public $likeCount;

    public function __construct($postId, $likeCount)
    {
        $this->postId = $postId;
        $this->likeCount = $likeCount;
    }

    public function broadcastOn()
    {
        return new Channel('posts');
    }

    public function broadcastAs()
    {
        return 'like.updated';
    }
    public function broadcastWith()
    {
        return [
            'postId' => $this->postId,
            'likeCount' => $this->likeCount,
        ];
    }
}
