<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('General Information')
                    ->schema([
                        TextEntry::make('woocommerce_id')
                            ->label('WooCommerce ID'),
                        TextEntry::make('email')
                            ->label('Email')
                            ->copyable(),
                        TextEntry::make('username')
                            ->placeholder('—'),
                        TextEntry::make('first_name')
                            ->label('First Name')
                            ->placeholder('—'),
                        TextEntry::make('last_name')
                            ->label('Last Name')
                            ->placeholder('—'),
                        TextEntry::make('role')
                            ->badge()
                            ->placeholder('—'),
                        TextEntry::make('avatar_url')
                            ->label('Avatar URL')
                            ->placeholder('—')
                            ->limit(50),
                        IconEntry::make('is_paying_customer')
                            ->label('Paying Customer')
                            ->boolean(),
                    ])->columns(3),

                Section::make('Billing Address')
                    ->schema([
                        KeyValueEntry::make('billing')
                            ->label('Billing Information')
                            ->placeholder('—'),
                    ]),

                Section::make('Shipping Address')
                    ->schema([
                        KeyValueEntry::make('shipping')
                            ->label('Shipping Information')
                            ->placeholder('—'),
                    ]),

                Section::make('Order Statistics')
                    ->schema([
                        TextEntry::make('orders_count')
                            ->label('Total Orders')
                            ->numeric(),
                        TextEntry::make('total_spent')
                            ->label('Total Spent')
                            ->money('USD'),
                        TextEntry::make('last_order_number')
                            ->label('Last Order Number')
                            ->placeholder('—'),
                        TextEntry::make('last_order_date')
                            ->label('Last Order Date')
                            ->dateTime()
                            ->placeholder('—'),
                    ])->columns(2),

                Section::make('Timestamps')
                    ->schema([
                        TextEntry::make('date_created')
                            ->label('Created')
                            ->dateTime(),
                        TextEntry::make('date_modified')
                            ->label('Modified')
                            ->dateTime(),
                    ])->columns(2)
                    ->collapsible(),
            ]);
    }
}
