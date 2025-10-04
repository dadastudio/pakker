<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')->label('Order #')->sortable()->searchable(),
                TextColumn::make('customer.email')->label('Customer')->searchable(),
                TextColumn::make('date_created')->dateTime('H:i:s d/m/Y ')->sortable(),
                TextColumn::make('lineItems.name')->label('Items')->listWithLineBreaks(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed', 'processing' => 'success',
                        'pending', 'on-hold' => 'warning',
                        'cancelled', 'failed', 'refunded', 'trash' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('total')
                    ->label('Total')
                    ->sortable()
                    ->formatStateUsing(fn ($state, Order $record): string => static::formatMoney($state, $record)),
                // Tables\Columns\TextColumn::make('total_tax')
                //     ->label('Tax total')
                //     ->formatStateUsing(fn ($state, Order $record): string => static::formatMoney($state, $record)),
            ])
            ->filters([
                SelectFilter::make('status')->options(self::getStatusOptions()),
                TernaryFilter::make('set_paid')->label('Paid')->nullable(),
            ])
            ->actions([
                Action::make('view')
                    ->label('View')
                    ->url(fn (Order $record) => OrderResource::getUrl('view', ['record' => $record]))
                    ->icon('heroicon-o-eye')
                    ->color('primary'),
                Action::make('edit')
                    ->label('Edit')
                    ->url(fn (Order $record) => OrderResource::getUrl('edit', ['record' => $record]))
                    ->icon('heroicon-o-pencil')
                    ->color('warning'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->visible(false),
                ])->label('Actions'),
            ])
            ->defaultSort('date_created', 'desc');
    }

    protected static function formatMoney(mixed $value, Order $order): string
    {
        $amount = $value === null ? 0.0 : (float) $value;
        $currency = strtoupper($order->currency ?? '');

        return trim(number_format($amount, 2).' '.$currency);
    }

    protected static function getStatusOptions(): array
    {
        return [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'on-hold' => 'On hold',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'refunded' => 'Refunded',
            'failed' => 'Failed',
            'trash' => 'Trash',
        ];
    }
}
