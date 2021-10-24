<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\PostLike;
use App\Models\User;
use App\Notifications\PostPublished;
use App\Repositories\Posts\EloquentPostRepository;
use App\Repositories\Posts\PostRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostsActionsTest extends TestCase
{
    use RefreshDatabase;

    /** @var \App\Repositories\Posts\PostRepository */
    private PostRepository $postRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->postRepository = new EloquentPostRepository(new Post);
    }

    /** @test */
    public function it_list_posts_paginated_with_likes_count_and_latest_five_reacters()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $post1 = Post::factory()
            ->for($user1, 'author')
            ->create([
                'description' => 'Post one',
                'created_at' => now()
            ]);

        $post2 = Post::factory()
            ->for($user1, 'author')
            ->create([
                'description' => 'Post two',
                'created_at' => now()->subDays(3)
            ]);

        PostLike::factory()
            ->for($post1, 'post')
            ->for($user2, 'reacter')
            ->create([
                'created_at' => now()->subDays(2)
            ]);

        PostLike::factory()
            ->for($post1, 'post')
            ->for($user1, 'reacter')
            ->create();

        $response = $this->get('/api/v1/posts');

        $response->assertOk()
            ->assertJsonStructure(['posts'])
            ->assertJsonCount(2, ['posts'])
            ->assertJsonStructure([
                'posts' => [
                    '*' => ['uuid', 'description', 'image', 'date', 'likes_count', 'reacters']
                ]
            ])
            ->assertJsonPath('posts.0.uuid', $post1->uuid)
            ->assertJsonPath('posts.0.likes_count', 2)
            ->assertJsonPath('posts.0.reacters', [
                $user1->name,
                $user2->name
            ])
            ->assertJsonPath('posts.1.uuid', $post2->uuid)
            ->assertJsonPath('posts.1.likes_count', 0)
            ->assertJsonPath('posts.1.reacters', []);
    }

    /** @test */
    public function an_authenticated_can_like_or_unlike_post()
    {
        $user = User::factory()->create();

        $post = Post::factory()
            ->for($user, 'author')
            ->create();

        $response = $this->actingAs($user)
            ->get(route('api:v1:posts:toggle-like', $post->uuid));

        $response->assertNoContent();

        $likes = $this->postRepository->findPostLikesByUuid($post->uuid);

        $this->assertEquals(1, $likes->count());
        $this->assertEquals($likes->first()->user_id, $user->uuid);

        $this->get(route('api:v1:posts:toggle-like', $post->uuid));

        $likes = $this->postRepository->findPostLikesByUuid($post->uuid);

        $this->assertEquals(0, $likes->count());
        $this->assertEmpty($likes);
    }

    /** @test */
    public function an_authenticated_user_can_delete_only_his_posts_with_stored_images()
    {
        Storage::fake('local');

        $user = User::factory()->create();

        $post = Post::factory()
            ->for($user, 'author')
            ->create([
                'image' => UploadedFile::fake()->image('post.jpg')->size(100)->store('posts')
            ]);

        $imagePath = $post->image;

        $this->assertTrue(Storage::exists($imagePath));

        $response = $this->actingAs($user)
            ->delete(route('api:v1:posts:delete', $post->uuid));

        $response->assertNoContent();

        $this->assertNull($this->postRepository->findByUuid($post->uuid));
        $this->assertFalse(Storage::exists($imagePath));
    }

    /** @test */
    public function it_throws_an_unauthorized_error_if_the_authenticated_user_try_to_delete_unrelated_post()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $post = Post::factory()
            ->for($user1, 'author')
            ->create([
                'image' => UploadedFile::fake()->image('post.jpg')->size(100)->store('posts')
            ]);

        $response = $this->actingAs($user2)
            ->delete(route('api:v1:posts:delete', $post->uuid));

        $response->assertForbidden();
    }

    /** @test */
    public function an_authenticated_user_can_create_a_new_post_and_post_created_notification_sent_to_other_users()
    {
        Storage::fake('local');
        Queue::fake();
        Notification::fake();

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $response = $this->actingAs($user1)
            ->post(route('api:v1:posts:store'), [
                'description' => 'Post of test',
                'image' => UploadedFile::fake()->image('post.jpg')->size(100)
            ]);

        Notification::assertSentTo(
            [$user2], PostPublished::class
        );

        Notification::assertNotSentTo(
            [$user1], PostPublished::class
        );

        $response->assertCreated()
            ->assertJsonStructure(['post'])
            ->assertJsonStructure(['post' => ['uuid', 'description', 'image', 'date', 'likes_count']])
            ->assertJsonPath('post.description', 'Post of test');
    }

    /** @test */
    public function it_delete_posts_older_than_15_days()
    {
        $user = User::factory()->create();

        $post1 = Post::factory()
            ->for($user, 'author')
            ->create([
                'created_at' => now()->subDays(15)
            ]);

        $post2 = Post::factory()
            ->for($user, 'author')
            ->create([
                'created_at' => now()->subDays(20)
            ]);

        $post3 = Post::factory()
            ->for($user, 'author')
            ->create([
                'created_at' => now()->subDays(10)
            ]);

        $post4 = Post::factory()
            ->for($user, 'author')
            ->create([
                'created_at' => now()
            ]);

        $this->assertEquals(4, $this->postRepository->findAll()->count());

        $this->artisan('posts:delete-older');

        $this->assertEquals(2, $this->postRepository->findAll()->count());
    }
}
