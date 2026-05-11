<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->required()
                ->live(onBlur: true),
            TextInput::make('slug')
                ->disabled()
                ->dehydrated(false)
                ->hiddenOn('create'),
            Textarea::make('description')
                ->nullable(),
            TextInput::make('icon')
                ->required()
                ->helperText('Heroicons kebab-case name, e.g. musical-note, briefcase'),
            TextInput::make('color')
                ->required()
                ->helperText('Tailwind color name, e.g. purple, blue, green'),
            TextInput::make('sort_order')
                ->numeric()
                ->default(0)
                ->required(),
            Toggle::make('is_active')
                ->default(true),
        ]);
    }
}
