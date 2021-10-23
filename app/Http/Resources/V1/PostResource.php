<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
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
