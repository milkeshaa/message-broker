<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;

class SlackChannel
{
    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        $message = $notification->toSlack($notifiable);

        // Send notification to the $notifiable instance...
    }
}
