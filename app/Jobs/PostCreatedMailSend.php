<?php

namespace App\Jobs;

use App\Mail\PostCreatedNotification;
use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class PostCreatedMailSend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public $post;

    /**
     * Create a new job instance.
     */
    public function __construct(Post $post)
    {
       $this->post = $post;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $admins=User::whereHas('roles', function($q){
            $q->where('title','admin');
        })->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new PostCreatedNotification($this->post));
        }
    }
}
