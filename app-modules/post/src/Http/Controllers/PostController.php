<?php

namespace Modules\Post\Http\Controllers;





use Inertia\Inertia;
use Illuminate\Support\Str;
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

        $posts = $query->with(['user'])
            ->withCount('comments', 'ratings')
            ->paginate(10)
            // This ensures all pagination metadata is included
            ->appends($request->query());

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
        // Uncomment if you want to use authorization
        // $this->authorize('create', Post::class);
        // dd($request);
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'media' => 'sometimes|array',
            // 'media.*' => 'sometimes|file|max:5120', // Optional media upload
        ]);

        $post = $request->user()->posts()->create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'slug' => Str::slug($validated['title']),
        ]);

        // Handle media upload if exists
        if (isset($validated['media'])) {
            $mediaFiles = [];
            foreach ($validated['media'] as $media) {
                $path = $media->store('posts', 'public');
                $mediaFiles[] = $path;
            }
            $post->addMedia($mediaFiles);
        }

        return redirect()->route('post.post.show', $post)
            ->with('success', 'Post created successfully');
    }

    public function show(Post $post)
    {
        // Load relationships and additional data
        $post->load([
            'user',
            'comments.user',
            'ratings'
        ])->loadCount([
            'comments',
            'ratings'
        ]);

        // Calculate average rating
        $averageRating = $post->ratings()->avg('rating');

        return Inertia::render('Post::Show', [
            'post' => $post,
            'averageRating' => $averageRating,
            // Optional: Check if current user has rated
            'userRating' => $post->ratings()->where('user_id', auth()->id())->first(),
        ]);
    }

    public function destroy(Post $post)
    {
        // Uncomment if you want to use authorization
        // $this->authorize('delete', $post);

        $post->delete();

        return redirect()->route('post.post.index')
            ->with('success', 'Post deleted successfully');
    }

    public function rate(Request $request, Post $post)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5'
        ]);

        $user = $request->user();
        if (!$user) {
            abort(403, 'User must be authenticated to rate a post.');
        }

        // // $request->user()
        $post->ratings()->updateOrCreate(
            ['user_id' => $user->id],
            ['rating' => $validated['rating']]
        );

        // $added = $post->ratings()->updateOrCreate(
        //     ['user_id' => $user->id, 'post_id' => $post->id], // Add 'post_id' to match the unique constraint
        //     ['rating' => $validated['rating']]
        // );

        // dd($added);

        return back()->with('success', 'Rating submitted successfully');
    }

    public function edit(Post $post)
    {
        // Uncomment if you want to use authorization
        // $this->authorize('update', $post);

        return Inertia::render('Post::Edit', [
            'post' => $post,
        ]);
    }

    public function update(Request $request, Post $post)
    {
        // Uncomment if you want to use authorization
        // $this->authorize('update', $post);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'media' => 'sometimes|array',
            'media.*' => 'sometimes|file|max:5120',
        ]);

        $post->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'slug' => Str::slug($validated['title']),
        ]);

        // Handle media upload if exists
        if (isset($validated['media'])) {
            $mediaFiles = [];
            foreach ($validated['media'] as $media) {
                $path = $media->store('posts', 'public');
                $mediaFiles[] = $path;
            }
            $post->addMedia($mediaFiles);
        }

        return redirect()->route('post.post.show', $post)
            ->with('success', 'Post updated successfully');
    }
}
