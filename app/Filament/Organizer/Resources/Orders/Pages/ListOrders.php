<?php

namespace App\Filament\Organizer\Resources\Orders\Pages;

use App\Filament\Organizer\Resources\Orders\OrderResource;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
