<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;

class TelegramChannel
{
    /**
     * Send the given notification.
     */
    public function send(?object $notifiable, Notification $notification): void
    {
        $message = $notification->toTelegram($notifiable);

        // Send notification to the $notifiable instance...
    }
}
