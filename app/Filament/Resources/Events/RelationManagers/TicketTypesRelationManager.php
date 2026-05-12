<?php

namespace App\Filament\Resources\Events\RelationManagers;

use App\Models\TicketType;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class TicketTypesRelationManager extends RelationManager
{
    protected static string $relationship = 'ticketTypes';

    public function isReadOnly(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
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
                IconColumn::make('is_group_ticket')
                    ->boolean()
                    ->label('Group'),
                TextColumn::make('group_size')
                    ->label('Group Size')
                    ->placeholder('—'),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
            ])
            ->defaultSort('sort_order')
            ->filters([
                TernaryFilter::make('is_group_ticket')
                    ->label('Group Ticket'),
                TernaryFilter::make('is_active')
                    ->label('Active'),
            ]);
    }
}
