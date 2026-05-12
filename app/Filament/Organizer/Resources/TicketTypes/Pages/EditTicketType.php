<?php

namespace App\Filament\Organizer\Resources\TicketTypes\Pages;

use App\Filament\Organizer\Resources\TicketTypes\TicketTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTicketType extends EditRecord
{
    protected static string $resource = TicketTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
