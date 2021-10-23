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
    public function __invoke(
        StoreRequest $storeRequest,
        PostRepository $postRepository,
        UserRepository $userRepository
    ): JsonResponse
    {
        $post = $postRepository->savePostForUser(Auth::user(), [
            'description' => $storeRequest->input('description'),
            'description' => '/posts/post-4.jpg'
        ]);

        $otherUsers = $userRepository->findAllExcept(Auth::user());

        Notification::send($otherUsers, new PostPublished($post));

        return response()->json([
            'post' => PostResource::make($post),
        ], Response::HTTP_CREATED);
    }
}
