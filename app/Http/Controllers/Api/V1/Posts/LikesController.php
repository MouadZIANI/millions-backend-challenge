<?php

namespace App\Http\Controllers\Api\V1\Posts;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\PostLikeResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LikesController extends Controller
{
    public function __invoke(Post $post): JsonResponse
    {
        return response()->json([
            'likes' => PostLikeResource::collection($post->likes)
        ], Response::HTTP_OK);
    }
}
