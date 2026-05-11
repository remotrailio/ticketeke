<?php

namespace App\Filament\Organizer\Resources\Events\Pages;

use App\Filament\Organizer\Resources\Events\EventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $data['organizer_id'] = $user->organizer->id;

        return $data;
    }
}
