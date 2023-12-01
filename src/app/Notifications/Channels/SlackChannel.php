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

        // here we can handle our custom way of sending slack notification along with other functionality
    }
}
