<?php

namespace App\Filament\Resources\Destinations\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DestinationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            FileUpload::make('image_path')
                ->label('Image')
                ->image()
                ->disk('r2')
                ->directory(app()->isLocal() ? 'local/destinations' : 'destinations')
                ->visibility('public')
                ->nullable(),
        ]);
    }
}
