@extends('layouts.app')

@section('content')
    <div class="flex justify-center">
        <div class="w-6/12 bg-white p-6 rounded-lg">
            @guest
                <p>Please login to create posts</p>
            @endguest
            @auth
                <form action="{{ route('posts') }}" method="post" class="mb-6">
                    @csrf
                    <div class="mb-4">
                        <label for="body" class="sr-only">Body</label>
                        <textarea name="body" id="body" cols="30" rows="4" class="bg-gray-100
                        border-2 w-full p-4 rounded-lg  @error('body') border-red-500 @enderror" placeholder="Post something!"></textarea>
                        @error('body')
                        <div class="text-red-500 mt-3 text-sm">
                            {{ $message }}
                        </div>
                    @enderror
                    </div>
                    <div>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded font-medium">Post</button>
                    </div>
                </form>
            @endauth 
            <div>
                @if ($posts->count())
                    @foreach ($posts as $post)
                        <div class="mb-4">
                            <div >
                                {{-- {{ $post->id }} --}}
                                <a href="{{ route('users.posts', $post->user) }}" class="font-bold">{{ $post->user->name }}</a> <span class="text-gray-600 text-sm">{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="mb-2">{{ $post->body }}</p>
                            <div class="flex items-center">
                                @if (!$post->likedBy(auth()->user()))
                                    <form action="{{ route('posts.likes', $post) }}" method="POST" class="mr-1">
                                        @csrf
                                        <button type="submit" class="text-blue-500">Like</button>
                                    </form>
                                @else
                                    <form action="{{ route('posts.likes', $post) }}" method="POST" class="mr-1">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="text-blue-500">Unlike</button>
                                    </form>
                                @endif
                                <span>{{ $post->likes->count() }} {{ Str::plural('like', $post->likes->count())}}</span>
                            </div>


                            {{-- @if($post->ownedBy(auth()->id())) --}} 

                            {{-- @if ($post->ownedBy(auth()->user())) --}}
                            @can('delete', $post)
                                <form action="{{ route('posts.destroy', $post->id) }}" method="post">
                                    @csrf
                                    @method('delete')
                                        <button type="submit" class="text-blue-500">Delete</button>
                                </form>
                            @endcan
                            {{-- @endif --}}
                        </div>
                    @endforeach
                    {{ $posts->links()}} 
                @else
                    <p>There are no posts</p>
                @endif
            </div>  
        </div>
        
    </div>
@endsection