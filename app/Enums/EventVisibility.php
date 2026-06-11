<?php

namespace App\Enums;

enum EventVisibility: string
{
    case PUBLIC   = 'public';
    case PRIVATE  = 'private';
    case UNLISTED = 'unlisted';
}
