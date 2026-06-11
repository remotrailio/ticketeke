<?php

namespace App\Filament\Organizer\Resources\TicketTypes\Pages;

use App\Filament\Organizer\Resources\TicketTypes\TicketTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTicketType extends CreateRecord
{
    protected static string $resource = TicketTypeResource::class;
}
