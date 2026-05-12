<?php

namespace App\Filament\Resources\Organizers\Schemas;

use App\Enums\OrganizerStatus;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class OrganizerForm
{
    public static function configure(Schema $schema): Schema
    {
        $editOnly = fn (Get $get, string $operation): bool => $operation === 'edit';

        return $schema->components([
            TextInput::make('display_name')
                ->required()
                ->disabled($editOnly)
                ->dehydrated(false),
            TextInput::make('slug')
                ->unique(table: 'organizers', column: 'slug', ignoreRecord: true)
                ->helperText('Leave blank to auto-generate from display name.')
                ->nullable()
                ->disabled($editOnly)
                ->dehydrated(false),
            Textarea::make('bio')
                ->nullable()
                ->disabled($editOnly)
                ->dehydrated(false),
            FileUpload::make('logo')
                ->image()
                ->disk('r2')
                ->directory(app()->isLocal() ? 'local/organizers/logo' : 'organizers/logo')
                ->visibility('public')
                ->nullable()
                ->disabled($editOnly)
                ->dehydrated(false),
            TextInput::make('email')
                ->email()
                ->nullable()
                ->disabled($editOnly)
                ->dehydrated(false),
            TextInput::make('phone')
                ->nullable()
                ->disabled($editOnly)
                ->dehydrated(false),
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
