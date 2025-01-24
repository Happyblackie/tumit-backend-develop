<?php

namespace App\Enums;

enum TumitaTumitEnum: string
{
    case PENDING = 'pending';
    case STALLED = 'stalled';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
}

