<?php

namespace App\Http\Controllers\Api\V1\Posts;

use App\Http\Controllers\Controller;
use App\Repositories\Posts\PostRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class DestroyController extends Controller
{
    public function __invoke(string $uuid, PostRepository $postRepository): Response
    {
        $post = $postRepository->findByUuid($uuid);

        $this->authorize('delete', $post);

        $post->delete();

        Storage::delete($post->image);

        return response()->noContent();
    }
}
