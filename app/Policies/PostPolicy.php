<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use App\Repositories\Posts\PostRepository;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * Determine whether the user can delete the post model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Post $post)
    {
        return $this->postRepository->isAuthorOfPost($user, $post);
    }
}
