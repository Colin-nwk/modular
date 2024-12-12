<?php

namespace Modules\Post\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Modules\Post\Models\Post;
use Modules\Post\Services\PostService;

class PostController
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(Request $request)
    {
        $posts = $this->postService->index($request);

        return Inertia::render('Post::Index', [
            'posts' => $posts
        ]);
    }

    public function create()
    {
        return Inertia::render('Post::Create', []);
    }

    public function store(Request $request)
    {
        $post = $this->postService->createPost($request);

        return redirect()->route('post.post.show', $post)
            ->with('success', 'Post created successfully');
    }

    public function show(Post $post)
    {
        $postDetails = $this->postService->getPostWithDetails($post);

        return Inertia::render('Post::Show', $postDetails);
    }

    public function destroy(Post $post)
    {
        $this->postService->deletePost($post);

        return redirect()->route('post.post.index')
            ->with('success', 'Post deleted successfully');
    }

    public function rate(Request $request, Post $post)
    {
        $this->postService->ratePost($request, $post);

        return back()->with('success', 'Rating submitted successfully');
    }

    public function edit(Post $post)
    {
        return Inertia::render('Post::Edit', [
            'post' => $post,
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $updatedPost = $this->postService->updatePost($request, $post);

        return redirect()->route('post.post.show', $updatedPost)
            ->with('success', 'Post updated successfully');
    }
}
