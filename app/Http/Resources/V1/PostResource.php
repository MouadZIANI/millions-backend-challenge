<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'uuid' => $this->uuid,
            'description' => $this->description,
            'image' => $this->image_url,
            'date' => $this->created_at->toDateTimeString(),
            'author' => UserResource::make($this->whenLoaded('author')),
            'likes_count' => $this->likes_count,
            'reacters' => $this->whenLoaded('reacters', function () {
                return $this->reacters->pluck('name');
            }),
        ];
    }
}
