<?php

namespace App\Filament\Resources\Events\Schemas;

use App\Enums\Country;
use App\Enums\EventStatus;
use App\Enums\EventVisibility;
use Cheesegrits\FilamentGoogleMaps\Fields\Map;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // Admin can assign/reassign organizer
            Select::make('organizer_id')
                ->relationship('organizer', 'display_name')
                ->searchable()
                ->required(),

            Select::make('category_id')
                ->relationship('category', 'name')
                ->required(),

            TextInput::make('title')
                ->required(),

            TextInput::make('slug')
                ->unique(table: 'events', column: 'slug', ignoreRecord: true)
                ->helperText('Leave blank to auto-generate from title.')
                ->nullable(),

            TextInput::make('excerpt')
                ->nullable(),

            Textarea::make('description')
                ->nullable(),

            FileUpload::make('banner_image')
                ->image()
                ->disk('r2')
                ->directory(app()->isLocal() ? 'local/events/banners' : 'events/banners')
                ->visibility('public')
                ->nullable(),

            Toggle::make('is_online')
                ->live()
                ->default(false),

            TextInput::make('meeting_url')
                ->url()
                ->required(fn (Get $get): bool => (bool) $get('is_online'))
                ->visible(fn (Get $get): bool => (bool) $get('is_online')),

            TextInput::make('venue_name')
                ->required(fn (Get $get): bool => ! (bool) $get('is_online')),

            TextInput::make('venue_address')
                ->nullable(),

            Select::make('destination_id')
                ->label('City')
                ->relationship('destination', 'name')
                ->searchable()
                ->preload()
                ->nullable()
                ->createOptionForm([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                ])
                ->createOptionUsing(function (array $data): int {
                    return \App\Models\Destination::create($data)->id;
                }),

            Select::make('country')
                ->options(Country::options())
                ->default(Country::KENYA->value)
                ->nullable(),

            Map::make('location')
                ->label('Location')
                ->defaultLocation([-1.286389, 36.817223]) // Nairobi
                ->defaultZoom(12)
                ->draggable()
                ->geolocate()
                ->height('400px')
                ->afterStateHydrated(function (Map $component, $record): void {
                    if ($record && $record->latitude && $record->longitude) {
                        $component->state([
                            'lat' => (float) $record->latitude,
                            'lng' => (float) $record->longitude,
                        ]);
                    }
                })
                ->afterStateUpdated(function ($state, Set $set): void {
                    $set('latitude', $state['lat'] ?? null);
                    $set('longitude', $state['lng'] ?? null);
                })
                ->live()
                ->dehydrated(false)
                ->visible(fn (Get $get): bool => ! (bool) $get('is_online'))
                ->nullable(),

            TextInput::make('latitude')
                ->numeric()
                ->readOnly()
                ->visible(fn (Get $get): bool => ! (bool) $get('is_online'))
                ->nullable(),

            TextInput::make('longitude')
                ->numeric()
                ->readOnly()
                ->visible(fn (Get $get): bool => ! (bool) $get('is_online'))
                ->nullable(),

            Select::make('timezone')
                ->options(
                    collect(timezone_identifiers_list())
                        ->mapWithKeys(fn ($tz) => [$tz => $tz])
                        ->all()
                )
                ->searchable()
                ->default('Africa/Nairobi')
                ->required(),

            DateTimePicker::make('start_at')
                ->required(),

            DateTimePicker::make('end_at')
                ->required()
                ->after('start_at'),

            Select::make('visibility')
                ->options([
                    EventVisibility::PUBLIC->value   => 'Public',
                    EventVisibility::PRIVATE->value  => 'Private',
                    EventVisibility::UNLISTED->value => 'Unlisted',
                ])
                ->default(EventVisibility::PUBLIC->value)
                ->required(),

            Select::make('status')
                ->options([
                    EventStatus::DRAFT->value     => 'Draft',
                    EventStatus::PUBLISHED->value => 'Published',
                    EventStatus::CANCELLED->value => 'Cancelled',
                    EventStatus::COMPLETED->value => 'Completed',
                ])
                ->default(EventStatus::DRAFT->value)
                ->live()
                ->required(),

            DateTimePicker::make('published_at')
                ->nullable()
                ->visible(fn (Get $get): bool => $get('status') === EventStatus::PUBLISHED->value),
        ]);
    }
}
