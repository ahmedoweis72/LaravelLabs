<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => $this->image,
            'image_url' => $this->image_url,
            'user' => new UserResource($this->whenLoaded('user')),
            'comments' => $this->whenLoaded('comments', function() {
                return $this->comments->map(function($comment) {
                    return [
                        'id' => $comment->id,
                        'content' => $comment->content,
                        'user' => new UserResource($comment->user),
                        'created_at' => $comment->created_at,
                        'updated_at' => $comment->updated_at,
                    ];
                });
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
