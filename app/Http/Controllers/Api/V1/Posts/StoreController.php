<?php

namespace App\Http\Controllers\Api\V1\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\StoreRequest;
use App\Http\Resources\V1\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $storeRequest): JsonResponse
    {
        $post = new Post;
        $post->description = $storeRequest->input('description');
        $post->image = '/posts/post-4.jpg';
        $post->author()->associate(Auth::user());
        $post->save();

        return response()->json([
            'post' => PostResource::make($post),
        ], Response::HTTP_CREATED);
    }
}
