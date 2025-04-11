<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return Inertia::render('Posts/Index', [
            'posts' => $posts
        ]);
    }

    public function show(Post $post)
    {
        // Route model binding will automatically find the post by slug
        $post->load(['comments.user', 'user']);
            
        return Inertia::render('Posts/Show', [
            'post' => $post
        ]);
    }

    public function create()
    {
        $users = User::all();
        
        return Inertia::render('Posts/Create', [
            'users' => $users
        ]);
    }

    public function edit(Post $post)
    {
        // Route model binding will automatically find the post by slug
        $users = User::all();
        
        return Inertia::render('Posts/Edit', [
            'post' => $post,
            'users' => $users,
        ]);
    }

    public function store(StorePostRequest $request)
    {
        // Form request validation happens automatically
        try {
            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'user_id' => $request->creator
            ];
            
            // Handle image upload if present
            if ($request->hasFile('image')) {
                $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
                $request->file('image')->storeAs('posts', $imageName, 'public');
                $data['image'] = $imageName;
            }
            
            Post::create($data);
            
            return to_route('posts.index')->with('success', 'Post created successfully!');
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error creating post: ' . $e->getMessage());
            
            // Return with error message
            return back()->withInput()->with('error', 'There was a problem creating your post. Please try again.');
        }
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        try {
            // Form request validation happens automatically
            // Route model binding will automatically find the post by slug
            
            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'user_id' => $request->creator,
            ];
            
            // Check if we need to remove the existing image
            if ($request->boolean('remove_image') && $post->image) {
                Storage::disk('public')->delete('posts/' . $post->image);
                $data['image'] = null;
            }
            // Handle image upload if present
            elseif ($request->hasFile('image')) {
                // Delete old image if exists
                if ($post->image) {
                    Storage::disk('public')->delete('posts/' . $post->image);
                }
                
                // Store new image
                $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
                $request->file('image')->storeAs('posts', $imageName, 'public');
                $data['image'] = $imageName;
            }
            
            $post->update($data);
           
            return to_route('posts.index')->with('success', 'Post updated successfully!');
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error updating post: ' . $e->getMessage());
            
            // Return with error message
            return back()->withInput()->with('error', 'There was a problem updating your post. Please try again.');
        }
    }

    public function destroy(Post $post)
    {
        try {
            // Route model binding will automatically find the post by slug
            
            // Image deletion is handled in the Post model's booted method
            $post->delete();

            return to_route('posts.index')->with('success', 'Post deleted successfully!');
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error deleting post: ' . $e->getMessage());
            
            // Return with error message
            return back()->with('error', 'There was a problem deleting the post. Please try again.');
        }
    }
}
