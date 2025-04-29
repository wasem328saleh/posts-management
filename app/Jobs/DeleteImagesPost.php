<?php

namespace App\Jobs;

use App\Traits\GeneralTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DeleteImagesPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,GeneralTrait;

    protected $post;
    /**
     * Create a new job instance.
     */
    public function __construct($post)
    {
        $this->post = $post;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $images=$this->post->images;
        foreach ($images as $image) {
            $url=$image->url;
            if (Str::startsWith($url,'/'))
            {
                File::delete(public_path($this->after('/',$url)));
            }else
            {
                File::delete(public_path($url));
            }
            $image->delete();
        }
    }
}
