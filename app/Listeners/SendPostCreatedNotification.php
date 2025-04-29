<?php

namespace App\Listeners;

use App\Jobs\PostCreatedMailSend;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPostCreatedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        PostCreatedMailSend::dispatch($event->post);
    }
}
