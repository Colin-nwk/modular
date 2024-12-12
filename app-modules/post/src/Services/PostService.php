<?php

namespace Modules\Post\Services;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\Post\Models\Post;

class PostService
{
    public function index(Request $request)
    {
        $query = Post::query();

        if ($request->search) {
            $query->where('title', 'LIKE', "%{$request->search}%")
                ->orWhere('content', 'LIKE', "%{$request->search}%");
        }

        return $query->with(['user'])
            ->withCount('comments', 'ratings')
            ->paginate(10)
            ->appends($request->query());
    }

    public function createPost(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'media' => 'sometimes|array',
        ]);

        $post = $request->user()->posts()->create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'slug' => Str::slug($validated['title']),
        ]);

        $this->handleMediaUpload($post, $validated);

        return $post;
    }

    public function updatePost(Request $request, Post $post)
    {
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

        $this->handleMediaUpload($post, $validated);

        return $post;
    }

    private function handleMediaUpload(Post $post, array $validated)
    {
        if (isset($validated['media'])) {
            $mediaFiles = [];
            foreach ($validated['media'] as $media) {
                $path = $media->store('posts', 'public');
                $mediaFiles[] = $path;
            }
            $post->addMedia($mediaFiles);
        }
    }

    public function getPostWithDetails(Post $post)
    {
        $post->load([
            'user',
            'comments.user',
            'ratings'
        ])->loadCount([
            'comments',
            'ratings'
        ]);

        $averageRating = $post->ratings()->avg('rating');

        return [
            'post' => $post,
            'averageRating' => $averageRating,
            'userRating' => $post->ratings()->where('user_id', auth()->id())->first(),
        ];
    }

    public function ratePost(Request $request, Post $post)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5'
        ]);

        $user = $request->user();
        if (!$user) {
            abort(403, 'User must be authenticated to rate a post.');
        }

        return $post->ratings()->updateOrCreate(
            ['user_id' => $user->id],
            ['rating' => $validated['rating']]
        );
    }

    public function deletePost(Post $post)
    {
        return $post->delete();
    }
}
