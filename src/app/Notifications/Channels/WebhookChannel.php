<?php

namespace App\Notifications\Channels;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class WebhookChannel
{
    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): Response|PromiseInterface
    {
        $requestData = $notification->toWebhook($notifiable);

        // here we can handle our custom way of sending webhook notification along with other functionality
        // now is very basic implementation
        return Http::asForm()->post($notification->message->receiver, $requestData);
    }
}
