<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\PostLike;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostsActionsTest extends TestCase
{
    use RefreshDatabase;

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
}
