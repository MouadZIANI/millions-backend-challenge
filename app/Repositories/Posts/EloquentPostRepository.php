<?php

namespace App\Repositories\Posts;

use App\Models\Post;
use App\Models\User;
use App\Models\PostLike;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentPostRepository implements PostRepository
{
    private Post $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function findByUuid(string $uuid, array $tobeLoadedRelations = []): ?Post
    {
        return $this->post->newQuery()
            ->with($tobeLoadedRelations)
            ->firstWhere('uuid', $uuid);
    }

    public function save(array $fields): ?Post
    {
        return $this->post->newQuery()->create($fields);
    }

    public function getPaginatedPostsWithLatestReacters(
        int $perPage,
        int $reactersLimit,
        array $columns = ['*']
    ): LengthAwarePaginator
    {
        return $this->post->newQuery()
            ->select($columns)
            ->latest()
            ->with('author')
            ->withCount('likes')
            ->withLastReacters($reactersLimit)
            ->paginate($perPage);
    }

    public function toggleLikeTo(User $user, Post $post): ?PostLike
    {
        if(
            $likedPost = $user->likes()->where('post_id', $post->uuid)->first()
        ) {
            return tap($likedPost)->delete();
        }

        return $user->likes()->create([
            'post_id' => $post->uuid
        ]);
    }

    public function isAuthorOfPost(User $user, Post $post): bool
    {
        return $user->posts()->where('uuid', $post->uuid)->exists();
    }

    public function findPostLikesByUuid(string $uuid): ?Collection
    {
        return $this->findByUuid($uuid)->likes()->get();
    }
}
