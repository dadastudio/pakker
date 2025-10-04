<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('woocommerce_id')
                    ->numeric(),
                TextEntry::make('parent.id')
                    ->label('Parent')
                    ->placeholder('-'),
                TextEntry::make('number')
                    ->placeholder('-'),
                TextEntry::make('order_key')
                    ->placeholder('-'),
                TextEntry::make('created_via')
                    ->placeholder('-'),
                TextEntry::make('version')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->placeholder('-'),
                TextEntry::make('currency')
                    ->placeholder('-'),
                TextEntry::make('date_created')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('date_created_gmt')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('date_modified')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('date_modified_gmt')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('discount_total')
                    ->numeric(),
                TextEntry::make('discount_tax')
                    ->numeric(),
                TextEntry::make('shipping_total')
                    ->numeric(),
                TextEntry::make('shipping_tax')
                    ->numeric(),
                TextEntry::make('cart_tax')
                    ->numeric(),
                TextEntry::make('total')
                    ->numeric(),
                TextEntry::make('total_tax')
                    ->numeric(),
                IconEntry::make('prices_include_tax')
                    ->boolean(),
                TextEntry::make('customer_woocommerce_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('customer.id')
                    ->label('Customer')
                    ->placeholder('-'),
                TextEntry::make('customer_ip_address')
                    ->placeholder('-'),
                TextEntry::make('customer_user_agent')
                    ->placeholder('-'),
                TextEntry::make('customer_note')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('payment_method')
                    ->placeholder('-'),
                TextEntry::make('payment_method_title')
                    ->placeholder('-'),
                TextEntry::make('transaction_id')
                    ->placeholder('-'),
                TextEntry::make('date_paid')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('date_paid_gmt')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('date_completed')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('date_completed_gmt')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('cart_hash')
                    ->placeholder('-'),
                IconEntry::make('set_paid')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
