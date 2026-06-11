<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('uuid')
                    ->label('UUID')
                    ->disabled()
                    ->dehydrated(false)
                    ->hiddenOn('create'),
                TextInput::make('name')
                    ->required(),
                Select::make('role')
                    ->options(['attendee' => 'Attendee', 'organizer' => 'Organizer', 'admin' => 'Admin'])
                    ->default('attendee'),
                TextInput::make('email')
                    ->label('Email address')
                    ->disabled()
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at')->disabled(),
                TextInput::make('password')
                    ->password()
                    ->disabled()
                    ->required(),
            ]);
    }
}
