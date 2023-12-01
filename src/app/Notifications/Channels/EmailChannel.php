<?php

namespace App\Notifications\Channels;

use Illuminate\Mail\SentMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;

class EmailChannel
{
    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): ?SentMessage
    {
        $message = $notification->toEmail($notifiable);

        // here we can handle our custom way of sending email along with other functionality
        return Mail::to($notification->message->receiver)->send($message);
    }
}
