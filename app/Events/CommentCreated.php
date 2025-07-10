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

class CommentCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    
    public $postId;
    public $comment;
    public $user;

    public function __construct($postId, $comment, $user)
    {
        $this->postId = $postId;
        $this->comment = $comment;
        $this->user = $user;
    }

    public function broadcastOn()
    {
        return new Channel('posts'); 
    }

    public function broadcastAs()
    {
        return 'comment.created';
    }

    public function broadcastWith()
    {
        return [
            'postId' => $this->postId,
            'comment' => [
                'comment' => $this->comment->comment,
            ],
            'user' => [
                'name' => $this->user->name,
                'profile_photo_url' => $this->user->profile_photo_url,
            ]
        ];
    }
}
