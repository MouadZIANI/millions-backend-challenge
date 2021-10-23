<?php

namespace App\Http\Controllers\Api\V1\Posts;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class DestroyController extends Controller
{
    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(Post $post): Response
    {
        $this->authorize('delete', $post);

        $post->delete();
        Storage::delete('storage' . $post->image);

        return response()->noContent();
    }
}
