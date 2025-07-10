<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
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

        return response()->json([
            'success' => true,
            'message' => 'Post created!',
            'post' => $post,
        ]);
    }
}
