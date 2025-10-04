<?php

namespace App\Filament\Resources\Customers\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('woocommerce_id')
                    ->label('WC ID')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('first_name')
                    ->label('First Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('last_name')
                    ->label('Last Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('username')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('role')
                    ->badge()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_paying_customer')
                    ->label('Paying')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('orders_count')
                    ->label('Orders')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_spent')
                    ->label('Total Spent')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('last_order_date')
                    ->label('Last Order')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('date_created')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('date_modified')
                    ->label('Modified')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_paying_customer')
                    ->label('Paying Customer')
                    ->nullable(),
            ])
            ->actions([
                Action::make('view')
                    ->label('View')
                    ->url(fn ($record) => \App\Filament\Resources\Customers\CustomerResource::getUrl('view', ['record' => $record]))
                    ->icon('heroicon-o-eye')
                    ->color('primary'),
                Action::make('edit')
                    ->label('Edit')
                    ->url(fn ($record) => \App\Filament\Resources\Customers\CustomerResource::getUrl('edit', ['record' => $record]))
                    ->icon('heroicon-o-pencil')
                    ->color('warning'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->visible(false),
                ])->label('Actions'),
            ])
            ->defaultSort('date_modified', 'desc');
    }
}
