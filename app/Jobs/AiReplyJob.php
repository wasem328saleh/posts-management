<?php

namespace App\Jobs;

use App\Models\AiBot;
use App\Models\Comment;
use App\Services\GeminiAIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AiReplyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $comment;
    /**
     * Create a new job instance.
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $geminiAIService=new GeminiAIService();
        $comment=$this->comment->comment;
        $response = $geminiAIService->generateContent($comment);

        $ai_bot=AiBot::first();
        if (!isset($response['error'])) {
            $reply=$response['candidates'][0]['content']['parts'][0]['text'];
            $this->comment->ai_reply()->create([
                'ai_reply'=>$reply,
                'bot_id'=>$ai_bot->id,
            ]);
        }
    }
}
