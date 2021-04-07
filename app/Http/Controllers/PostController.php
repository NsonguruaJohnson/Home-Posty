<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;

class PostController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->only('store');
    }

    public function index(){
        $posts = Post::with('user', 'likes')->latest()->paginate(20);
        // dd($posts);
        // dd($posts->first()->created_at->toTimeDateString());
        // dd($posts->first()->created_at->toTimeString());
        // dd($posts->first()->created_at->diffForHumans());
        # Read more on the carbon documentation
        return view('posts.index', [
            'posts' => $posts
        ]);
    }

    public function store(Request $request){
        // dd('posted');
        $this->validate($request,[
            'body' => 'required'
        ]);
        
        auth()->user()->posts()->create($request->only('body'));
        // $request->user()->posts()->create($request->only('body')); #This works well as the above
        return back();
    }

    public function destroy(Request $request, Post $post){
        // dd($post);
        // dd('delete', $id);
        // $post = Post::find($id);
        // dd($post);

        // if (!$post->ownedBy($request->user())){
        //     // throw new AuthorizationException();
        //     return response(null, 401);
        // }
        # Using authorization
        $this->authorize('delete', $post);

        $post->delete();
        return back();
    }

    
}
