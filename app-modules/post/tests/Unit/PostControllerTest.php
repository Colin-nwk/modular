<?php

namespace Tests\Feature\Post;

use Tests\TestCase;
use App\Models\User;
use Modules\Post\Models\Post;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function authenticated_user_can_view_posts_index()
    {
        $this->actingAs($this->user);

        Post::factory()->count(5)->create();

        $response = $this->get(route('post.post.index'));

        $response->assertInertia(
            fn(Assert $page) =>
            $page->component('Post::Index')
                ->has('posts.data', 5)
        );
    }

    /** @test */
    public function authenticated_user_can_create_a_post()
    {
        $this->actingAs($this->user);

        $postData = [
            'title' => 'New Test Post',
            'content' => 'This is a test post content',
        ];

        $response = $this->post(route('post.post.store'), $postData);

        $this->assertDatabaseHas('posts', [
            'title' => 'New Test Post',
            'user_id' => $this->user->id
        ]);

        $response->assertRedirect();
    }

    /** @test */
    public function authenticated_user_can_view_single_post()
    {
        $this->actingAs($this->user);

        $post = Post::factory()->create();

        $response = $this->get(route('post.post.show', $post));

        $response->assertInertia(
            fn(Assert $page) =>
            $page->component('Post::Show')
                ->has('post')
        );
    }

    /** @test */
    public function authenticated_user_can_update_their_own_post()
    {
        $this->actingAs($this->user);

        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $updateData = [
            'title' => 'Updated Post Title',
            'content' => 'Updated post content',
        ];

        $response = $this->put(route('post.post.update', $post), $updateData);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Post Title',
        ]);

        $response->assertRedirect();
    }

    /** @test */
    public function authenticated_user_can_delete_their_own_post()
    {
        $this->actingAs($this->user);

        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $response = $this->delete(route('post.post.destroy', $post));

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);

        $response->assertRedirect(route('post.post.index'));
    }

    /** @test */
    public function authenticated_user_can_rate_a_post()
    {
        $this->actingAs($this->user);

        $post = Post::factory()->create();

        $response = $this->post(route('post.post.rate', $post), [
            'rating' => 4
        ]);

        $this->assertDatabaseHas('ratings', [
            'post_id' => $post->id,
            'user_id' => $this->user->id,
            'rating' => 4
        ]);

        $response->assertRedirect();
    }
}
