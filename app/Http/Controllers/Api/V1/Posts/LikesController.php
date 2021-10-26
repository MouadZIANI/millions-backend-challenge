<?php

namespace App\Http\Controllers\Api\V1\Posts;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\PostLikeResource;
use App\Repositories\Posts\PostRepository;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LikesController extends Controller
{
    public function __invoke(string $uuid, PostRepository $postRepository): JsonResponse
    {
        return response()->json([
            'likes' => PostLikeResource::collection(
                $postRepository->findPostLikesByUuid($uuid)
            ),
        ], Response::HTTP_OK);
    }
}
