<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')->paginate(10);
        
        // Return a collection of posts as a resource
        return  PostResource::collection($posts);
    }

    public function show($slug)
    {
        // return Post::where('slug', $slug)->firstOrFail();
        $post = Post::with('user')->where('slug', $slug)->firstOrFail();
        return new PostResource($post);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:3|max:255|unique:posts,title',
            'description' => 'required|string|min:10',
            'user_id' => 'required|exists:users,id',
            'image' => 'nullable|image|mimes:jpg,png|max:2048'
        ]);
        
        $data = $request->only(['title', 'description', 'user_id']);
        
        // Handle image upload if present
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('posts', $imageName, 'public');
            $data['image'] = $imageName;
        }
        
        $post = Post::create($data);
        return response()->json($post, 201);
    }

    public function update(Request $request, $slug)
    {
        $request->validate([
            'title' => 'required|string|min:3',
            'description' => 'required|string|min:10',
            'user_id' => 'required|exists:users,id',
        ]);
        $post = Post::where('slug', $slug)->firstOrFail();
        $post->update($request->all());
        return $post;
    }

    public function destroy($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully'], 200);
    }

}
