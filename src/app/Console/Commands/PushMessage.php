<?php

namespace App\Console\Commands;

use App\Enums\ChannelEnum;
use App\Enums\NotificationTypeEnum;
use App\Models\Message;
use App\Notifications\BirthdayDigestNotification;
use App\Notifications\InvitationNotification;
use App\Notifications\ReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Notifications\AnonymousNotifiable;

class PushMessage extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'message:push
                            {channel : Channel to which system should push the message}
                            {type : Type of the message}
                            {body : Content of the message}
                            {metadata?* : Optional parameter for any additional message data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command pushes message into specified channel queue';

    /**
     * Prompt for missing input arguments using the returned questions.
     *
     * @return array
     */
    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'channel' => [
                'In which channel do you want to push the message?',
                sprintf('E.g. %s', implode(', ', ChannelEnum::values()))
            ],
            'type' => [
                'What\'s the type of the message?',
                sprintf('E.g. %s', implode(', ', NotificationTypeEnum::values()))
            ],
            'body' => 'Please, provide body of the message below.',
            'metadata' => 'Please, provide any additional data if needed in any form, or leave it empty.',
        ];
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // TODO: use some libs from available
        try {
            $channel = $this->getChannel();
            $receiver = $this->getReceiver($channel);
            $type = $this->getType();
            $body = $this->argument(key: 'body');
            $metadata = json_encode($this->argument(key: 'metadata') ?? []);
        } catch (\Throwable $exception) {
            $this->error(
                string: $exception->getMessage()
            );
            return;
        }

        // we can pass url or any needed additional data in the metadata property and use it in the handlers
        $message = Message::create([
            'channel' => $channel,
            'type' => $type,
            'body' => $body,
            'receiver' => $receiver,
            'metadata' => $metadata,
        ]);
        $notification = $this->getNotificationTemplate($message);
        /*
            using anonymous notifiable class here since don't have notifiable instance
            in real-world it can be user model, or any other notifiable model
        */
        $anonymousNotification = new AnonymousNotifiable();
        $anonymousNotification->notify($notification->onQueue($channel->value));
    }

    /**
     * @throws \Exception
     */
    private function getChannel(): ChannelEnum
    {
        $channel = ChannelEnum::tryFrom($this->argument(key: 'channel'));
        if (null === $channel) {
            throw new \Exception(sprintf(
                'Provided channel is not available, please try one from the following %s',
                implode(', ', ChannelEnum::values())
            ));
        }
        return $channel;
    }

    /**
     * @throws \Exception
     */
    private function getReceiver(ChannelEnum $channel): string
    {
        $receiver = '';
        if (ChannelEnum::EMAIL === $channel) {
            $receiver = $this->ask('Please, provide receivers email');
            if (!$receiver) {
                throw new \Exception('Receiver of email is required.');
            }
        }
        return $receiver;
    }

    /**
     * @throws \Exception
     */
    private function getType(): NotificationTypeEnum
    {
        $type = NotificationTypeEnum::tryFrom($this->argument(key: 'type'));
        if (null === $type) {
            throw new \Exception(sprintf(
                'Provided type is not available, please try one from the following %s',
                implode(', ', NotificationTypeEnum::values())
            ));
        }
        return $type;
    }

    private function getNotificationTemplate(Message $message): \Illuminate\Notifications\Notification
    {
        return match ($message->type) {
            NotificationTypeEnum::INVITATION => new InvitationNotification($message),
            NotificationTypeEnum::REMINDER => new ReminderNotification($message),
            NotificationTypeEnum::BIRTHDAY_DIGEST => new BirthdayDigestNotification($message),
        };
    }
}