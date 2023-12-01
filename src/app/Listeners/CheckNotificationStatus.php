<?php

namespace App\Listeners;

use Illuminate\Notifications\Events\NotificationSending;
use Illuminate\Support\Facades\Log;

class CheckNotificationStatus
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
    public function handle(NotificationSending $event): void
    {
        Log::channel($event->notification?->queue)->info(
            sprintf('Notification (%s) is processing.', $event->notification->id)
        );
    }
}
