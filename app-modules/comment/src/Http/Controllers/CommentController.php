<?php

namespace Modules\Comment\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Post\Models\Post;
use Modules\Comment\Models\Comment;

class CommentController
{
    public function store(Request $request, Post $post)
    {

        // dd($request->user());

        $validated = $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        // dd($request);
        // dd($post->id);

        $request->user()->comments()->create([
            'content' => $validated['content'],
            'post_id' => $request['post_id']
        ]);


        return back()->with('success', 'Comment added successfully');
    }

    public function destroy(Comment $comment)
    {
        // Optional: Add authorization check
        // $this->authorize('delete', $comment);

        $comment->delete();

        return back()->with('success', 'Comment deleted successfully');
    }
}
