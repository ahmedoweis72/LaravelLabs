<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|min:3|max:1000'
        ], [
            'content.required' => 'Please provide some content for your comment',
            'content.min' => 'Your comment should be at least 3 characters',
            'content.max' => 'Your comment is too long (maximum 1000 characters)'
        ]);
        
        $post->comments()->create([
            'content' => $request->content,
            'user_id' => Auth::id()
        ]);

        return redirect()->back();
    }

    public function update(Request $request, Post $post, $commentId)
    {
        $request->validate([
            'content' => 'required|string|min:3|max:1000'
        ], [
            'content.required' => 'Please provide some content for your comment',
            'content.min' => 'Your comment should be at least 3 characters',
            'content.max' => 'Your comment is too long (maximum 1000 characters)'
        ]);

        $comment = Comment::findOrFail($commentId);
        
        // Check if user owns this comment
        if ($comment->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'You are not authorized to edit this comment');
        }
        
        $comment->update([
            'content' => $request->content
        ]);

        return redirect()->back();
    }

    public function destroy(Post $post, $commentId)
    {
        $comment = Comment::findOrFail($commentId);
        
        // Check if user owns this comment
        if ($comment->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'You are not authorized to delete this comment');
        }
        
        $comment->delete();
        
        return redirect()->back();
    }
}