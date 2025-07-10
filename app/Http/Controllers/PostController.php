<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Events\LikeUpdated;
use App\Events\PostCreated;
use Illuminate\Http\Request;
use App\Events\CommentCreated;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'comments.user', 'likes'])
            ->latest()
            ->get();

        return view('dashboard', compact('posts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'nullable|string',
            'image' => 'nullable|image'
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
        }

        $post = Post::create([
            'user_id' => Auth::id(),
            'description' => $validated['description'],
            'image_path' => $path
        ]);

        broadcast(new PostCreated($post->load('user')))->toOthers();

        

        return response()->json([
            'success' => true,
            'message' => 'Post created!',
            'post' => $post,
        ]);
    }

    public function like(Post $post)
    {
        $user = auth()->user();

        if ($post->likes()->where('user_id', $user->id)->exists()) {
            $post->likes()->where('user_id', $user->id)->delete();
        } else {
            $post->likes()->create(['user_id' => $user->id]);
        }

        broadcast(new LikeUpdated($post->id, $post->likes->count()))->toOthers();
        // broadcast(new LikeUpdated($post->id, $post->likes->count()));

        return response()->json([
            'postId' => $post->id,
            'likeCount' => $post->likes()->count(),
        ]);
    }

    public function fetchAllComments(Post $post)
    {
        $comments = $post->comments()->with('user')->latest()->get();
        $comments = $comments->map(function ($comment) {
            return [
                'id' => $comment->id,
                'comment' => $comment->comment,
                'user' => $comment->user,
                'time' => $comment->created_at->diffForHumans(),
            ];
        });

        return response()->json($comments);
    }

    public function storeComment(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'comment' => 'required|string|max:500',
        ]);
        $post_id = $request->post_id;

        $comment = Comment::create([
            'post_id' => $post_id,
            'user_id' => auth()->id(),
            'comment' => $request->comment,
        ]);

        broadcast(new CommentCreated($post_id, $comment, auth()->user()))->toOthers();


        return response()->json([
            'message' => 'Comment posted successfully.',
            'comment' => $comment->load('user'),
            'user' => $comment->user,
            'time' => $comment->created_at->diffForHumans(),
        ]);
    }
}
