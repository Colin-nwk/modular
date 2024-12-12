<?php

namespace Modules\Post\Http\Controllers;

// namespace Modules\Foobar\Http\Controllers;



use Inertia\Inertia;
use Illuminate\Http\Request;
use Modules\Post\Models\Post;

class PostController
{
    public function index(Request $request)
    {
        $query = Post::query();

        if ($request->search) {
            $query->where('title', 'LIKE', "%{$request->search}%")
                ->orWhere('content', 'LIKE', "%{$request->search}%");
        }

        $posts = $query->with(['user', 'ratings'])
            ->withCount('comments', 'ratings')
            ->paginate(10)
            // This ensures all pagination metadata is included
            ->appends($request->query());

        return Inertia::render('Post::Index', [
            'posts' => $posts
        ]);
    }


    // public function index(Request $request)
    // {
    //     $query = Post::query();

    //     if ($request->search) {
    //         $query->where('title', 'LIKE', "%{$request->search}%")
    //             ->orWhere('content', 'LIKE', "%{$request->search}%");
    //     }

    //     return Inertia::render('Post::Index', [
    //         'posts' => $query->with(['user', 'ratings'])->paginate(10)
    //     ]);
    // }

    // public function store(Request $request)
    // {
    //     $this->authorize('create', Post::class);

    //     $validated = $request->validate([
    //         'title' => 'required|max:255',
    //         'content' => 'required'
    //     ]);

    //     $post = $request->user()->posts()->create($validated);

    //     return redirect()->route('posts.show', $post);
    // }

    // public function rate(Request $request, Post $post)
    // {
    //     $validated = $request->validate([
    //         'rating' => 'required|integer|between:1,5'
    //     ]);

    //     $post->ratings()->updateOrCreate(
    //         ['user_id' => $request->user()->id],
    //         ['rating' => $validated['rating']]
    //     );

    //     return back();
    // }
}
