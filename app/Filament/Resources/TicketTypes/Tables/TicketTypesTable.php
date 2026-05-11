<?php

namespace App\Filament\Resources\TicketTypes\Tables;

use App\Models\Organizer;
use App\Models\TicketType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Components\Select;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TicketTypesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('event.title')
                    ->label('Event')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('event.organizer.display_name')
                    ->label('Organizer')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price')
                    ->money(fn (TicketType $record) => $record->currency)
                    ->sortable(),
                TextColumn::make('quantity')
                    ->sortable(),
                TextColumn::make('sold')
                    ->sortable(),
                TextColumn::make('available')
                    ->label('Available')
                    ->state(fn (TicketType $record): int => $record->availableQuantity()),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                TextColumn::make('sort_order')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->filters([
                SelectFilter::make('event_id')
                    ->relationship('event', 'title')
                    ->label('Event')
                    ->searchable(),
                Filter::make('organizer_id')
                    ->label('Organizer')
                    ->schema([
                        Select::make('organizer_id')
                            ->label('Organizer')
                            ->options(Organizer::query()->pluck('display_name', 'id')->all())
                            ->searchable(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['organizer_id'] ?? null,
                            fn ($q, $id) => $q->whereHas(
                                'event',
                                fn ($q) => $q->where('organizer_id', $id)
                            )
                        );
                    }),
                TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
