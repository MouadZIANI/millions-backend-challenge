<?php

namespace App\Repositories\Posts;

use App\Models\Post;
use App\Models\PostLike;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface PostRepository
{
    public function findByUuid(string $uuid): ?Post;

    public function save(array $fields): ?Post;

    public function findPostLikesByUuid(string $uuid);

    public function getPaginatedPostsWithLatestReacters(
        int $perPage,
        int $reactersLimit,
        array $columns = ['*']
    ): ?LengthAwarePaginator;

    public function toggleLikeTo(User $user, Post $post): ?PostLike;

    public function isAuthorOfPost(User $user, Post $post): bool;

    public function deletePostsOlderThanGivenDays(int $days);
}
