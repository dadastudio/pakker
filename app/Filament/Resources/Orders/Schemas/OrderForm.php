<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('General')
                    ->schema([
                        TextInput::make('woocommerce_id')
                            ->label('WooCommerce ID')
                            ->numeric()
                            ->disabled(),
                        TextInput::make('number')
                            ->label('Order Number')
                            ->disabled(),
                        Select::make('status')
                            ->options(self::getStatusOptions())
                            ->required(),
                        TextInput::make('currency')
                            ->maxLength(10)
                            ->default('USD'),
                        Toggle::make('set_paid')
                            ->label('Paid'),
                    ])->columns(2),

                Section::make('Customer')
                    ->schema([
                        Select::make('customer_id')
                            ->label('Customer')
                            ->relationship('customer', 'email')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('customer_ip_address')
                            ->label('IP Address')
                            ->maxLength(100),
                        Textarea::make('customer_note')
                            ->label('Customer Note')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Totals')
                    ->schema([
                        TextInput::make('total')
                            ->numeric()
                            ->required()
                            ->prefix('$'),
                        TextInput::make('total_tax')
                            ->label('Tax Total')
                            ->numeric()
                            ->prefix('$'),
                        TextInput::make('discount_total')
                            ->label('Discount Total')
                            ->numeric()
                            ->prefix('$'),
                        TextInput::make('discount_tax')
                            ->label('Discount Tax')
                            ->numeric()
                            ->prefix('$'),
                        TextInput::make('shipping_total')
                            ->label('Shipping Total')
                            ->numeric()
                            ->prefix('$'),
                        TextInput::make('shipping_tax')
                            ->label('Shipping Tax')
                            ->numeric()
                            ->prefix('$'),
                    ])->columns(3),

                Section::make('Addresses')
                    ->schema([
                        KeyValue::make('billing')
                            ->label('Billing '),
                        KeyValue::make('shipping')
                            ->label('Shipping '),
                    ])->columns(2),

                Section::make('Payment')
                    ->schema([
                        TextInput::make('payment_method')
                            ->maxLength(100),
                        TextInput::make('payment_method_title')
                            ->maxLength(255),
                        TextInput::make('transaction_id')
                            ->label('Transaction ID')
                            ->maxLength(255),
                        DateTimePicker::make('date_paid')
                            ->label('Date Paid'),
                    ])->columns(2),

                Section::make('Dates')
                    ->schema([
                        DateTimePicker::make('date_created')
                            ->label('Created')
                            ->disabled(),
                        DateTimePicker::make('date_completed')
                            ->label('Completed'),
                    ])->columns(2)
                    ->collapsible(),
            ])->columns(1);
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
