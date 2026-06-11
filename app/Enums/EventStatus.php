<?php

namespace App\Enums;

enum EventStatus: string
{
    case DRAFT     = 'draft';
    case PUBLISHED = 'published';
    case LIVE      = 'live';
    case CANCELLED = 'cancelled';
    case COMPLETED = 'completed';
    case ENDED     = 'ended';
}
