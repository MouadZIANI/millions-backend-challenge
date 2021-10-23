<?php

namespace App\Http\Controllers\Api\V1\Posts;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ToggleLikeController extends Controller
{
    public function __invoke(Post $post): Response
    {
        Auth::user()->toggleLikeTo($post);

        return response()->noContent();
    }
}
