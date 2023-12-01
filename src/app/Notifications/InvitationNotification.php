<?php

namespace App\Notifications;

use App\Enums\ChannelEnum;
use App\Mail\InvitationMail;
use App\Models\Message;
use App\Notifications\Channels\EmailChannel;
use App\Notifications\Channels\SlackChannel;
use App\Notifications\Channels\SmsChannel;
use App\Notifications\Channels\TelegramChannel;
use App\Notifications\Channels\WebhookChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class InvitationNotification extends Notification implements ShouldQueue
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
    public function toEmail(object $notifiable): InvitationMail
    {
        return new InvitationMail($this->message);
    }

    /**
     * Get the sms representation of the notification.
     *
     * As a return type here can be any SmsViewClass, or any from trusted libs
     */
    public function toSms(object $notifiable): array
    {
        return [

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

        ];
    }

    /**
     * Get the webhook representation of the notification.
     *
     * As a return type here can be any SlackViewClass, or any from trusted libs
     */
    public function toSlack(object $notifiable): array
    {
        return [

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

    public function failed(\Exception $e): void
    {
        Log::channel($this->queue)->info(
            sprintf('Notification (%s) failed with exception: %s.', $this->id, $e->getMessage())
        );
    }
}
