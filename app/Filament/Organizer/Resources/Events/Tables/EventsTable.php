<?php

namespace App\Filament\Organizer\Resources\Events\Tables;

use App\Enums\EventStatus;
use App\Enums\EventVisibility;
use App\Filament\Organizer\Resources\TicketTypes\TicketTypeResource;
use App\Models\Event;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
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
                TextColumn::make('destination.name')
                    ->label('City')
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
                SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Category')
                    ->searchable(),
            ])
            ->recordActions([
                Action::make('ticket_types')
                    ->label('Ticket Types')
                    ->icon('heroicon-o-ticket')
                    ->url(fn (Event $record): string => TicketTypeResource::getUrl('index', ['event' => $record->uuid])),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function (DeleteBulkAction $action, \Illuminate\Support\Collection $records) {
                            $blocked = $records->filter(fn (Event $e) => ! $e->isDeletable());

                            if ($blocked->isEmpty()) {
                                return;
                            }

                            Notification::make()
                                ->warning()
                                ->title($blocked->count() . ' event(s) cannot be deleted')
                                ->body('Events that have started or have paid orders were skipped.')
                                ->send();

                            if ($blocked->count() === $records->count()) {
                                $action->halt();
                            }
                        }),
                ]),
            ]);
    }
}
