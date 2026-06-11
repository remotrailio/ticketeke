<?php

namespace App\Filament\Organizer\Resources\Events\Pages;

use App\Filament\Organizer\Resources\Events\EventResource;
use App\Filament\Organizer\Resources\TicketTypes\TicketTypeResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('ticket_types')
                ->label('Ticket Types')
                ->icon('heroicon-o-ticket')
                ->url(fn (): string => TicketTypeResource::getUrl('index', ['event' => $this->record->uuid])),
            DeleteAction::make(),
        ];
    }
}
