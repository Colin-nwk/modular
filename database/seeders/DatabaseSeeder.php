<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Modules\Post\Models\Post;
use Illuminate\Database\Seeder;
use Modules\Comment\Models\Comment;
use Modules\Post\Models\PostRating;
use Database\Seeders\UsersRolesPermissionsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([UsersRolesPermissionsSeeder::class]);
        $this->call([PostCommentSeeder::class]);

        // UsersRolesPermissionsSeeder::$called


        // Create a post using a random existing user
        $post = Post::factory(5)->create();

        // Create a post for a specific user
        $user = User::find(1);
        $post = Post::factory()->forUser($user)->create();


        Comment::factory(5)->create();


        $user = User::find(1);
        $post = Post::find(1);
        Comment::factory()->forUserAndPost($user, $post)->create();

        // Similar methods work for PostRating
        // PostRating::factory(1)->create();
        // PostRating::factory(1)->forUserAndPost($user, $post)->create();
    }
}
