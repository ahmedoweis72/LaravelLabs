<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public $posts;
    function __construct()
    { }

    public function index()
    {
        $posts = Post::paginate(4);
        return view('posts.index', compact('posts'));
    }

    public function show($postId)
    {
        $post = Post::where('id', $postId)->with('comments.user')->first();
        return view('posts.show', compact('post'));
    }

    public function create()
    {
        $users = User::all();
        return view('posts.create', [
            'users' => $users
        ]);
    }

    public function edit($postId)
    {
        $post = Post::where('id', $postId)->first();
        $users = User::all();
        return view('posts.update', [
            'post' => $post,
            'users' => $users,
            
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'creator' => 'required|exists:users,id',
        ]);

        Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => $request->creator
        ]);

        return to_route('posts.index');
    }

    public function storeUpdate(Request $request, $postId)
    {
       
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'creator' => 'required|exists:users,id',
        ]);

       
        $post = Post::findOrFail($postId);

        
        $post->update([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => $request->creator,
        ]);

       
        return to_route('posts.index');
    }

    public function destroy($postId)
    {
        $post = Post::findOrFail($postId);
        $post->delete();

        return to_route('posts.index');
    }
}
