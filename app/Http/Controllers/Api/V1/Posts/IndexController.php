<?php

namespace App\Http\Controllers\Api\V1\Posts;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'posts' => PostResource::collection(
                Post::query()
                    ->with('author')
                    ->withCount('likes')
                    ->withLastReacters(5)
                    ->latest()
                    ->paginate()
            )
        ], Response::HTTP_OK);
    }
}
