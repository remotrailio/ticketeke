<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING   = 'pending';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case EXPIRED   = 'expired';
    case REFUNDED  = 'refunded';
}
