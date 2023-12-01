<?php

namespace App\Enums;

enum ChannelEnum : string
{
    case TELEGRAM = 'telegram';
    case SMS = 'sms';
    case EMAIL = 'email';
    case WEBHOOK = 'webhook';
    case SLACK = 'slack';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
