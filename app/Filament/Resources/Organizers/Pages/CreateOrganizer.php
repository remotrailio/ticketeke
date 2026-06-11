<?php

namespace App\Filament\Resources\Organizers\Pages;

use App\Enums\UserRole;
use App\Filament\Resources\Organizers\OrganizerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrganizer extends CreateRecord
{
    protected static string $resource = OrganizerResource::class;

    protected function afterCreate(): void
    {
        $this->record->user?->update(['role' => UserRole::ORGANIZER]);
    }
}
