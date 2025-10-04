<?php

namespace App\Filament\Resources\ProductVariations\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductVariationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required(),
                TextInput::make('product_woocommerce_id')
                    ->required()
                    ->numeric(),
                TextInput::make('woocommerce_id')
                    ->required()
                    ->numeric(),
                Textarea::make('permalink')
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('status'),
                TextInput::make('menu_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('sku')
                    ->label('SKU'),
                TextInput::make('price')
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('regular_price')
                    ->numeric(),
                TextInput::make('sale_price')
                    ->numeric(),
                DateTimePicker::make('date_on_sale_from'),
                DateTimePicker::make('date_on_sale_from_gmt'),
                DateTimePicker::make('date_on_sale_to'),
                DateTimePicker::make('date_on_sale_to_gmt'),
                Toggle::make('on_sale')
                    ->required(),
                Toggle::make('purchasable')
                    ->required(),
                Toggle::make('virtual')
                    ->required(),
                Toggle::make('downloadable')
                    ->required(),
                TextInput::make('download_limit')
                    ->numeric(),
                TextInput::make('download_expiry')
                    ->numeric(),
                TextInput::make('tax_status'),
                TextInput::make('tax_class'),
                Toggle::make('manage_stock')
                    ->required(),
                TextInput::make('stock_quantity')
                    ->numeric(),
                TextInput::make('stock_status'),
                TextInput::make('backorders'),
                Toggle::make('backordered')
                    ->required(),
                Toggle::make('visible')
                    ->required(),
                Toggle::make('shipping_required')
                    ->required(),
                Toggle::make('shipping_taxable')
                    ->required(),
                TextInput::make('weight')
                    ->numeric(),
                TextInput::make('dimensions'),
                TextInput::make('shipping_class'),
                TextInput::make('shipping_class_id')
                    ->numeric(),
                Textarea::make('price_html')
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->image(),
                TextInput::make('links'),
                TextInput::make('meta_data_snapshot'),
                DateTimePicker::make('date_created'),
                DateTimePicker::make('date_created_gmt'),
                DateTimePicker::make('date_modified'),
                DateTimePicker::make('date_modified_gmt'),
            ]);
    }
}
