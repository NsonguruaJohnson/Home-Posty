<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostLikeController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function store(Request $request, Post $post){
        // dd($post);
        if ($post->likedBy($request->user())){
            return response(null, 409); #409 is a conflict http code
            #You could do a lot of operations here like show a message etc
        }
        // dd($post->likedBy($request->user())); #Check if the particular post has been liked by the currently authenticated user
        $post->likes()->create([
            'user_id' => $request->user()->id
        ]);

        return back();
    }

    public function destroy(Request $request, Post $post){
        // dd($post);
        $request->user()->likes()->where('post_id', $post->id)->delete();
        
        return back();
    }
}
