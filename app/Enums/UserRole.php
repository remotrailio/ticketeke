<?php

namespace App\Enums;

enum UserRole: string
{
    case ATTENDEE = 'attendee';
    case ORGANIZER = 'organizer';
    case ADMIN = 'admin';
}