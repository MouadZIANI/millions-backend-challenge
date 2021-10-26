<?php

namespace App\Http\Controllers\Api\V1\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\StoreRequest;
use App\Http\Resources\V1\PostResource;
use App\Notifications\PostPublished;
use App\Repositories\Posts\PostRepository;
use App\Repositories\Users\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\Response;

class StoreController extends Controller
{
    /** @var \App\Repositories\Posts\PostRepository */
    private PostRepository $postRepository;

    /** @var \App\Repositories\Users\UserRepository */
    private UserRepository $userRepository;

    public function __construct(PostRepository $postRepository, UserRepository $userRepository)
    {
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
    }

    public function __invoke(StoreRequest $storeRequest): JsonResponse
    {
        $imagePath = $storeRequest->file('image')->store('posts');

        $post = $this->postRepository->save([
            'description' => $storeRequest->input('description'),
            'image' => $imagePath,
            'user_id' => Auth::id(),
        ]);

        $otherUsers = $this->userRepository->findAllExcept(Auth::user());

        Notification::send($otherUsers, new PostPublished($post));

        return response()->json([
            'post' => PostResource::make($post),
        ], Response::HTTP_CREATED);
    }
}
