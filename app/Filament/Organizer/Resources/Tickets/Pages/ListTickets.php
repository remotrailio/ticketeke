<?php

namespace App\Filament\Organizer\Resources\Tickets\Pages;

use App\Filament\Organizer\Resources\Tickets\TicketResource;
use Filament\Resources\Pages\ListRecords;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
