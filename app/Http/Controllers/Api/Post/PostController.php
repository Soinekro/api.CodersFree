<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostResource;
use App\Models\Post\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->except(['index','show']);
        $this->middleware('scopes:read-post')->only(['index','show']);
        $this->middleware(['scopes:create-post','can:create post'])->only(['store']);
        $this->middleware(['scopes:update-post','can:edit post'])->only(['update']);
        $this->middleware(['scopes:delete-post','can:delete post'])->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::included()
        ->filter()->sort()->getOrPaginate();

        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=> 'required|max:255',
            'slug'=> 'required|max:255|unique:posts',
            'extract'=> 'required',
            'body'=>'required',
            'category_id'=>'required|exists:categories,id',
        ]);

        $user = auth()->user();
        $data['user_id']=$user->id;

        $post = Post::create($data);

        return PostResource::make($post);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::included()->findOrFail($id);
        return PostResource::make($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('author',$post);

        $request->validate([
            'name'=> 'required|max:255',
            'slug'=> 'required|max:255|unique:posts,slug,'.$post->id,
            'extract'=> 'required',
            'body'=>'required',
            'category_id'=>'required|exists:categories,id',
        ]);

       $post->update($request->all());

       return PostResource::make($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $this->authorize('author',$post);
        $post->delete();
        return PostResource::make($post);

    }
}
