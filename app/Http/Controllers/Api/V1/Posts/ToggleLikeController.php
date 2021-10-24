<?php

namespace App\Http\Controllers\Api\V1\Posts;

use App\Http\Controllers\Controller;
use App\Repositories\Posts\PostRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ToggleLikeController extends Controller
{
    public function __invoke(string $uuid, PostRepository $postRepository): Response
    {
        $post = $postRepository->findByUuid($uuid);

        $postRepository->toggleLikeTo(Auth::user(), $post);

        return response()->noContent();
    }
}
