<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserInfoResource extends JsonResource
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
            'name'=>$this->name,
            'email'=>$this->email,
            'image_profile'=>url($this->image_profile->url),
            'posts'=>$this->whenLoaded('posts',function (){
                return PostResource::collection($this->posts->load(['comments','images']));
            }),
            'post_images'=>$this->whenLoaded('post_images',function (){
                return ImageResource::collection($this->post_images);
            })
        ];
    }
}
