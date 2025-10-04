<?php

namespace App\Filament\Resources\Orders\Resources\OrderLineItems\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderLineItemInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('order.id')
                    ->label('Order'),
                TextEntry::make('woocommerce_id')
                    ->numeric(),
                TextEntry::make('name')
                    ->placeholder('-'),
                TextEntry::make('product.name')
                    ->label('Product')
                    ->placeholder('-'),
                TextEntry::make('variation.id')
                    ->label('Variation')
                    ->placeholder('-'),
                TextEntry::make('woocommerce_product_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('woocommerce_variation_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('quantity')
                    ->numeric(),
                TextEntry::make('tax_class')
                    ->placeholder('-'),
                TextEntry::make('subtotal')
                    ->numeric(),
                TextEntry::make('subtotal_tax')
                    ->numeric(),
                TextEntry::make('total')
                    ->numeric(),
                TextEntry::make('total_tax')
                    ->numeric(),
                TextEntry::make('sku')
                    ->label('SKU')
                    ->placeholder('-'),
                TextEntry::make('price')
                    ->money(),
                TextEntry::make('parent_name')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
