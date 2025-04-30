<?php

namespace App\Listeners;

use App\Jobs\AiReplyJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AiReplyListeners
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        AiReplyJob::dispatch($event->comment);
    }
}
