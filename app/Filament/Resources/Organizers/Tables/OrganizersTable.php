<?php

namespace App\Filament\Resources\Organizers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrganizersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('banner')
                    ->disk('r2')
                    ->width(120)
                    ->imageHeight(40)
                    ->extraImgAttributes(['class' => 'object-cover rounded']),

                ImageColumn::make('logo')
                    ->disk('r2')
                    ->circular()
                    ->defaultImageUrl(fn () => 'https://ui-avatars.com/api/?name=O&background=random'),

                TextColumn::make('display_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('platform_fee_percentage')
                    ->label('Fee %')
                    ->suffix('%')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                IconColumn::make('verified')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
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
