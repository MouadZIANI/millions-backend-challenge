<?php

namespace App\Http\Controllers\Api\V1\Posts;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Repositories\Posts\PostRepository;
use App\Http\Resources\V1\PostLikeResource;
use Symfony\Component\HttpFoundation\Response;

class LikesController extends Controller
{
    public function __invoke(int $uuid, PostRepository $postRepository): JsonResponse
    {
        return response()->json([
            'likes' => PostLikeResource::collection(
                $postRepository->findPostLikesByUuid($uuid)
            )
        ], Response::HTTP_OK);
    }
}
