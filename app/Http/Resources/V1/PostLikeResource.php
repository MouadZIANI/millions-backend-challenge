<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class PostLikeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'uuid' => $this->uuid,
            'reacted_at' => $this->created_at->toDateTimeString(),
            'author' => UserResource::make($this->reacter)
        ];
    }
}
