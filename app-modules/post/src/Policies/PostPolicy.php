<?php

namespace Modules\Post\Policies;

use App\Models\User;
use Modules\Post\Models\Post;

class PostPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $user)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create posts');
    }

    public function update(User $user, Post $post)
    {
        return $user->id === $post->user_id ||
            $user->hasPermissionTo('edit all posts');
    }

    public function delete(User $user, Post $post)
    {
        return $user->id === $post->user_id ||
            $user->hasPermissionTo('delete all posts');
    }
}
