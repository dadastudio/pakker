<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('General Information')
                    ->schema([
                        TextInput::make('woocommerce_id')
                            ->label('WooCommerce ID')
                            ->required()
                            ->numeric()
                            ->disabled(),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('username')
                            ->maxLength(255),
                        TextInput::make('first_name')
                            ->maxLength(255),
                        TextInput::make('last_name')
                            ->maxLength(255),
                        TextInput::make('role')
                            ->maxLength(100),
                        TextInput::make('avatar_url')
                            ->label('Avatar URL')
                            ->url()
                            ->maxLength(500),
                        Toggle::make('is_paying_customer')
                            ->label('Paying Customer'),
                    ])->columns(2),

                Section::make('Billing Address')
                    ->schema([
                        KeyValue::make('billing')
                            ->label('Billing Information')
                            ->columnSpanFull(),
                    ]),

                Section::make('Shipping Address')
                    ->schema([
                        KeyValue::make('shipping')
                            ->label('Shipping Information')
                            ->columnSpanFull(),
                    ]),

                Section::make('Order Statistics')
                    ->schema([
                        TextInput::make('orders_count')
                            ->label('Total Orders')
                            ->numeric()
                            ->default(0)
                            ->disabled(),
                        TextInput::make('total_spent')
                            ->label('Total Spent')
                            ->numeric()
                            ->default(0.0)
                            ->prefix('$')
                            ->disabled(),
                        TextInput::make('last_order_number')
                            ->label('Last Order Number')
                            ->disabled(),
                        DateTimePicker::make('last_order_date')
                            ->label('Last Order Date')
                            ->disabled(),
                    ])->columns(2),

                Section::make('Timestamps')
                    ->schema([
                        DateTimePicker::make('date_created')
                            ->label('Created')
                            ->disabled(),
                        DateTimePicker::make('date_modified')
                            ->label('Modified')
                            ->disabled(),
                    ])->columns(2)
                    ->collapsible(),
            ]);
    }
}
