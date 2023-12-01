<?php

namespace App\Enums;

enum NotificationTypeEnum : string
{
    case INVITATION = 'invitation';
    case REMINDER = 'reminder';
    case BIRTHDAY_DIGEST = 'birthday_digest';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
