<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, $postId)
    {
        $request->validate([
            'body' => 'required|string'
        ]);

        $post = Post::findOrFail($postId);
        
        $post->comments()->create([
            'body' => $request->body,
            'user_id' => $request->user_id
        ]);

        return to_route('posts.show', $postId);
    }

    public function update(Request $request, $postId, $commentId)
    {
        $request->validate([
            'body' => 'required|string'
        ]);

        $comment = Comment::findOrFail($commentId);
        
        $comment->update([
            'body' => $request->body
        ]);

        return to_route('posts.show', $postId);
    }

    public function destroy($postId, $commentId)
    {
        $comment = Comment::findOrFail($commentId);
        
        $comment->delete();
        
        return to_route('posts.show', $postId);
    }
}