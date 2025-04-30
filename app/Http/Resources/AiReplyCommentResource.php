<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AiReplyCommentResource extends JsonResource
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
            'image_bot'=>url($this->ai_bot->image_profile->url),
            'bot_name'=>$this->ai_bot->bot_name,
            'reply'=>$this->ai_reply,
        ];
    }
}
