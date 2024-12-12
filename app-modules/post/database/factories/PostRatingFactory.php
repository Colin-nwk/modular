<?php

namespace Modules\Post\Database\Factories;

use App\Models\User;
use Modules\Post\Models\Post;
use Modules\Post\Models\PostRating;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Post\Models\Model>
 */
class PostRatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    // public function definition(): array
    // {
    //     return [
    //         //
    //     ];
    // }

    protected $model = PostRating::class;

    public function definition(): array
    {
        // Get a random existing user
        $user = User::inRandomOrder()->first();

        // Get a random existing post
        $post = Post::inRandomOrder()->first();

        // Fallback if no users or posts exist
        if (!$user) {
            $user = User::factory()->create();
        }
        if (!$post) {
            $post = Post::factory()->create();
        }

        return [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'rating' => $this->faker->numberBetween(1, 5),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    // Method to create a rating for a specific user and post
    public function forUserAndPost(User $user, Post $post)
    {
        return $this->state(function (array $attributes) use ($user, $post) {
            return [
                'user_id' => $user->id,
                'post_id' => $post->id,
            ];
        });
    }
}
