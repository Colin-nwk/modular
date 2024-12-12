<?php

namespace Modules\Post\Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use Modules\Post\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Post\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    // public function definition(): array
    // {
    //     $users = User::all();
    //     return [
    //         'title' => $this->faker->sentence(),
    //         'content' => $this->faker->paragraph(4),
    //         'user_id'=>$users->
    //     ];
    // }

    protected $model = Post::class;

    public function definition(): array
    {
        // Get a random existing user
        $user = User::inRandomOrder()->first();

        // If no users exist, you might want to fall back to creating a new user
        if (!$user) {
            $user = User::factory()->create();
        }

        $title = $this->faker->sentence();
        return [
            'user_id' => $user->id,
            'title' => $title,
            'content' => $this->faker->paragraphs(3, true),
            'slug' => Str::slug($title),
            'media' => $this->generateMedia(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    // Method to create a post for a specific user
    public function forUser(User $user)
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'user_id' => $user->id,
            ];
        });
    }

    protected function generateMedia(): array
    {
        if ($this->faker->boolean(50)) {
            return array_map(function () {
                return $this->faker->imageUrl();
            }, range(1, $this->faker->numberBetween(1, 3)));
        }
        return [];
    }
}
