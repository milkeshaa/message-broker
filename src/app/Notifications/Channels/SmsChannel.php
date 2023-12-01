<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;

class SmsChannel
{
    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        $message = $notification->toSms($notifiable);

        // here we can handle our custom way of sending sms notification along with other functionality
    }
}
