<?php

namespace App\Filament\Resources\Events\Tables;

use App\Enums\EventStatus;
use App\Enums\EventVisibility;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('banner_image')
                    ->label('Image')
                    ->disk('r2')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('organizer.display_name')
                    ->label('Organizer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('visibility')
                    ->badge(),
                TextColumn::make('start_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('end_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('destination.name')
                    ->label('City')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('start_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        EventStatus::DRAFT->value     => 'Draft',
                        EventStatus::PUBLISHED->value => 'Published',
                        EventStatus::CANCELLED->value => 'Cancelled',
                        EventStatus::COMPLETED->value => 'Completed',
                    ]),
                SelectFilter::make('visibility')
                    ->options([
                        EventVisibility::PUBLIC->value   => 'Public',
                        EventVisibility::PRIVATE->value  => 'Private',
                        EventVisibility::UNLISTED->value => 'Unlisted',
                    ]),
                SelectFilter::make('organizer_id')
                    ->relationship('organizer', 'display_name')
                    ->label('Organizer')
                    ->searchable(),
                SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Category')
                    ->searchable(),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
