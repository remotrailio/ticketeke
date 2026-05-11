<?php

namespace App\Filament\Resources\TicketTypes\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class TicketTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('event_id')
                ->relationship('event', 'title')
                ->searchable()
                ->required(),

            TextInput::make('name')
                ->required(),

            Textarea::make('description')
                ->nullable(),

            TextInput::make('price')
                ->numeric()
                ->minValue(0)
                ->required(),

            Select::make('currency')
                ->options([
                    'kes' => 'KES – Kenyan Shilling',
                    'usd' => 'USD – US Dollar',
                    'eur' => 'EUR – Euro',
                    'gbp' => 'GBP – British Pound',
                ])
                ->default('kes')
                ->required(),

            TextInput::make('quantity')
                ->numeric()
                ->minValue(1)
                ->required(),

            TextInput::make('sold')
                ->numeric()
                ->disabled()
                ->dehydrated(false)
                ->hiddenOn('create'),

            TextInput::make('min_per_order')
                ->numeric()
                ->minValue(1)
                ->default(1)
                ->required(),

            TextInput::make('max_per_order')
                ->numeric()
                ->default(10)
                ->rules([
                    fn (Get $get) => function ($attribute, $value, $fail) use ($get) {
                        if ((int) $value < (int) $get('min_per_order')) {
                            $fail('Max per order must be greater than or equal to min per order.');
                        }
                    },
                ])
                ->required(),

            DateTimePicker::make('sales_start')
                ->nullable(),

            DateTimePicker::make('sales_end')
                ->after('sales_start')
                ->nullable(),

            TextInput::make('sort_order')
                ->numeric()
                ->default(0)
                ->required(),

            Toggle::make('is_active')
                ->default(true),
        ]);
    }
}
