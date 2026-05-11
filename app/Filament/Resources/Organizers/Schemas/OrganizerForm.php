<?php

namespace App\Filament\Resources\Organizers\Schemas;

use App\Enums\OrganizerStatus;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class OrganizerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('display_name')
                ->required(),
            TextInput::make('slug')
                ->unique(table: 'organizers', column: 'slug', ignoreRecord: true)
                ->helperText('Leave blank to auto-generate from display name.')
                ->nullable(),
            Textarea::make('bio')
                ->nullable(),
            FileUpload::make('logo')
                ->image()
                ->disk('public')
                ->directory('organizers/logos')
                ->nullable(),
            TextInput::make('email')
                ->email()
                ->nullable(),
            TextInput::make('phone')
                ->nullable(),
            Toggle::make('verified')
                ->default(false),
            Select::make('status')
                ->options([
                    OrganizerStatus::ACTIVE->value    => 'Active',
                    OrganizerStatus::SUSPENDED->value => 'Suspended',
                ])
                ->default(OrganizerStatus::ACTIVE->value)
                ->required(),
        ]);
    }
}
