<?php

namespace App\Listeners;

use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Log;

class LogNotification
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
    public function handle(NotificationSent $event): void
    {
        Log::channel($event->notification?->queue)->info(
            sprintf('Notification (%s) sent with response: %s.', $event->notification->id, json_encode($event->response))
        );
    }
}
