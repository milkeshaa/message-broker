<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $casts = [
        'metadata' => 'array',
    ];

    protected $fillable = [
        'channel',
        'type',
        'body',
        'receiver',
        'metadata',
    ];
}
