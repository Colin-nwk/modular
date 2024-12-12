<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Modules\Post\Models\Post;
use Illuminate\Database\Seeder;
use Modules\Comment\Models\Comment;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PostCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        // Get existing users or create if not enough
        $users = User::count() > 5
            ? User::inRandomOrder()->limit(5)->get()
            : User::factory()->count(5)->create();

        // Generate 50 posts
        $posts = collect();
        foreach (range(1, 50) as $posts) {
            $title = fake()->unique()->sentence(6);
            $post = Post::create([
                'user_id' => $users->random()->id,
                'title' => $title,
                'content' => fake()->paragraphs(3, true),
                'slug' => Str::slug($title),
                'created_at' => fake()->dateTimeBetween('-1 year', 'now')
            ]);

            // $posts->push($post);

            // // Add ratings for the post
            // $this->addRandomRatings($post, $users);
        }

        // Generate comments
        $posts->each(function ($post) use ($users) {
            // Generate 0-10 comments per post
            $commentCount = rand(0, 10);

            foreach (range(1, $commentCount) as $comments) {
                Comment::create([
                    'post_id' => $post->id,
                    'user_id' => $users->random()->id,
                    'content' => fake()->paragraph(),
                    'created_at' => fake()->dateTimeBetween($post->created_at, 'now')
                ]);
            }
        });
    }

    private function addRandomRatings($post, $users)
    {
        // Add 1-5 random ratings per post
        $ratingUsers = $users->random(rand(1, 5));

        $ratingUsers->each(function ($user) use ($post) {
            $post->ratePost($user, rand(1, 5));
        });
    }
}
