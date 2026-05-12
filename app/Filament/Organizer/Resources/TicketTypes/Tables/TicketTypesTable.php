<?php

namespace App\Filament\Organizer\Resources\TicketTypes\Tables;

use App\Models\TicketType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class TicketTypesTable
{
    public static function configure(Table $table): Table
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
                    ->placeholder('—')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                TextColumn::make('sort_order')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active'),
                TernaryFilter::make('is_group_ticket')
                    ->label('Group Ticket'),
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
