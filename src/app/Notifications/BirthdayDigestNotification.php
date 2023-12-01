<?php

namespace App\Notifications;

use App\Enums\ChannelEnum;
use App\Mail\BirthdayDigestMail;
use App\Models\Message;
use App\Notifications\Channels\EmailChannel;
use App\Notifications\Channels\SlackChannel;
use App\Notifications\Channels\SmsChannel;
use App\Notifications\Channels\TelegramChannel;
use App\Notifications\Channels\WebhookChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class BirthdayDigestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public readonly Message $message
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param object $notifiable
     * @return string
     */
    public function via(object $notifiable): string
    {
        return match ($this->message->channel) {
            ChannelEnum::EMAIL => EmailChannel::class,
            ChannelEnum::WEBHOOK => WebhookChannel::class,
            ChannelEnum::SLACK => SlackChannel::class,
            ChannelEnum::TELEGRAM => TelegramChannel::class,
            ChannelEnum::SMS => SmsChannel::class,
        };
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toEmail(object $notifiable): BirthdayDigestMail
    {
        return new BirthdayDigestMail($this->message);
    }

    /**
     * Get the sms representation of the notification.
     *
     * As a return type here can be any SmsViewClass, or any from trusted libs
     */
    public function toSms(object $notifiable): array
    {
        return [
            'header' => 'Birthday digest SMS!',
            'footer' => 'Kind regards!'
        ];
    }

    /**
     * Get the telegram representation of the notification.
     *
     * As a return type here can be any TelegramViewClass, or any from trusted libs
     */
    public function toTelegram(object $notifiable): array
    {
        return [
            // here we can use any TelegramViewClass which can prepare any shape of the out message
        ];
    }

    /**
     * Get the webhook representation of the notification.
     *
     * As a return type here can be any WebhookViewClass, or any from trusted libs
     */
    public function toWebhook(object $notifiable): array
    {
        return [
            'body' => $this->message->body,
            'metadata' => $this->message->metadata,
        ];
    }

    /**
     * Get the slack representation of the notification.
     *
     * As a return type here can be any SlackViewClass, or any from trusted libs
     */
    public function toSlack(object $notifiable): array
    {
        return [
            // here we can use any SlackViewClass which can prepare any shape of the out message
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function failed(\Throwable $e): void
    {
        Log::channel($this->queue)->info(
            sprintf('Notification (%s) failed with exception: %s.', $this->id, $e->getMessage())
        );
    }
}
