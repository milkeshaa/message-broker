<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;

class WebhookChannel
{
    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        $message = $notification->toWebhook($notifiable);

        // Send notification to the $notifiable instance...
    }
}
