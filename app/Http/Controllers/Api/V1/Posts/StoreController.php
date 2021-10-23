<?php

namespace App\Http\Controllers\Api\V1\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\StoreRequest;
use App\Http\Resources\V1\PostResource;
use App\Models\Post;
use App\Repositories\Posts\PostRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $storeRequest, PostRepository $postRepository): JsonResponse
    {

        $post = $postRepository->savePostForUser(Auth::user(), [
            'description' => $storeRequest->input('description'),
            'description' => '/posts/post-4.jpg'
        ]);

        //$this->no

        return response()->json([
            'post' => PostResource::make($post),
        ], Response::HTTP_CREATED);
    }
}
