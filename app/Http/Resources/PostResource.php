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
            'id'=>$this->id,
            'title'=>$this->title,
            'content'=>$this->content,
            'has_images'=>$this->hasImages(),
            'count_images'=>$this->whenLoaded('images', function () {
                return $this->images()->count();
            }),
            'images'=>$this->whenLoaded('images', function () {
                return ImageResource::collection($this->images);
            }),
            'count_comments'=>$this->whenLoaded('comments', function () {
                return $this->comments()->count();
            }),
            'comments'=>$this->whenLoaded('comments', function () {
                return CommentResource::collection($this->comments()->with(['user','ai_reply'])->get());
            })
        ];
    }
}
