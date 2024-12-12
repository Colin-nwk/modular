<?php

namespace Tests\Unit\Post;

use Mockery;
use Tests\TestCase;
use Illuminate\Http\Request;
use Modules\Post\Models\Post;
use Illuminate\Support\Facades\Auth;
use Modules\Post\Services\PostService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $postService;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->postService = app(PostService::class);
        $this->user = \App\Models\User::factory()->create();
        Auth::login($this->user);
    }

    /** @test */

    public function it_can_create_a_post()
    {
        $request = Request::create('/posts', 'POST', [
            'title' => 'Test Post',
            'content' => 'Test Content',
        ]);
        $request->setUserResolver(function () {
            return $this->user;
        });

        $post = $this->postService->createPost($request);

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'content' => 'Test Content',
            'user_id' => $this->user->id,
            'slug' => 'test-post'
        ]);
        $this->assertEquals('Test Post', $post->title);
    }

    /** @test */
    public function it_can_update_an_existing_post()
    {
        $post = Post::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Original Title',
        ]);

        $request = Request::create("/posts/{$post->id}", 'PUT', [
            'title' => 'Updated Title',
            'content' => 'Updated Content',
        ]);
        $request->setUserResolver(function () {
            return $this->user;
        });

        $updatedPost = $this->postService->updatePost($request, $post);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Title',
            'content' => 'Updated Content',
            'slug' => 'updated-title'
        ]);
    }

    /** @test */
    public function it_can_retrieve_paginated_posts()
    {
        Post::factory()->count(15)->create();

        $request = Request::create('/posts', 'GET');
        $posts = $this->postService->index($request);

        $this->assertCount(10, $posts);
        $this->assertEquals(15, $posts->total());
    }

    /** @test */
    public function it_can_rate_a_post()
    {
        $post = Post::factory()->create();

        $request = Request::create("/posts/{$post->id}/rate", 'POST', [
            'rating' => 4
        ]);
        $request->setUserResolver(function () {
            return $this->user;
        });

        $rating = $this->postService->ratePost($request, $post);

        $this->assertDatabaseHas('ratings', [
            'post_id' => $post->id,
            'user_id' => $this->user->id,
            'rating' => 4
        ]);
    }

    /** @test */
    public function it_throws_validation_error_for_invalid_rating()
    {
        $post = Post::factory()->create();

        $request = Request::create("/posts/{$post->id}/rate", 'POST', [
            'rating' => 6 // Invalid rating
        ]);
        $request->setUserResolver(function () {
            return $this->user;
        });

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $this->postService->ratePost($request, $post);
    }
}
