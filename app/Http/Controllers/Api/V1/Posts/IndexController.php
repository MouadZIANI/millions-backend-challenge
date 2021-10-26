<?php

namespace App\Http\Controllers\Api\V1\Posts;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\PostResource;
use App\Repositories\Posts\PostRepository;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{
    public function __invoke(PostRepository $postRepository): JsonResponse
    {
        return response()->json([
            'posts' => PostResource::collection(
                $postRepository->getPaginatedPostsWithLatestReacters(15, 5)
            ),
        ], Response::HTTP_OK);
    }
}
