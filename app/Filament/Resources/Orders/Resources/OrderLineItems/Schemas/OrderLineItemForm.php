<?php

namespace App\Filament\Resources\Orders\Resources\OrderLineItems\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderLineItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Order Information')
                    ->description('Associate this line item with an order')
                    ->schema([
                        Select::make('order_id')
                            ->label('Order')
                            ->relationship('order', 'number')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])

                    ->collapsible(),

                Section::make('Product Details')
                    ->description('Product and variation information')
                    ->schema([

                        Select::make('product_id')
                            ->label('Product')
                            ->relationship('product', 'id')
                            ->searchable(),

                        TextInput::make('name')
                            ->label('Product Name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('sku')
                            ->label('SKU')
                            ->maxLength(100),

                        // Select::make('variation_id')
                        // 	->label('Variation')
                        // 	->relationship('variation.attributes',)
                        // 	->searchable()
                        // 	->preload(),

                        // TextInput::make('parent_name')
                        // 	->label('Parent Product Name')
                        // 	->maxLength(255)
                        // 	->helperText('For product variations, the parent product name'),
                    ])
                    ->columns(1)
                    ->collapsible(),

                Section::make('Pricing & Quantity')
                    ->description('Pricing, quantity, and tax information')
                    ->schema([
                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(1),

                        TextInput::make('price')
                            ->label('Unit Price')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->default(0.00)
                            ->prefix('$'),

                        TextInput::make('tax_class')
                            ->label('Tax Class')
                            ->maxLength(100),

                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->default(0.00)
                            ->prefix('$'),

                        TextInput::make('subtotal_tax')
                            ->label('Subtotal Tax')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->default(0.00)
                            ->prefix('$'),

                        TextInput::make('total')
                            ->label('Total')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->default(0.00)
                            ->prefix('$'),

                        TextInput::make('total_tax')
                            ->label('Total Tax')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->default(0.00)
                            ->prefix('$'),

                        KeyValue::make('taxes')
                            ->label('Tax Breakdown')
                            ->keyLabel('Tax ID')
                            ->valueLabel('Amount')
                            ->reorderable(false),
                    ])
                    ->columns(1)
                    ->collapsible(),

                Section::make('WooCommerce Integration')
                    ->description('WooCommerce-specific identifiers')
                    ->schema([
                        TextInput::make('woocommerce_id')
                            ->label('WooCommerce Line Item ID')
                            ->numeric()
                            ->minValue(1),

                        TextInput::make('woocommerce_product_id')
                            ->label('WooCommerce Product ID')
                            ->numeric()
                            ->minValue(1),

                        TextInput::make('woocommerce_variation_id')
                            ->label('WooCommerce Variation ID')
                            ->numeric()
                            ->minValue(1),
                    ])
                    ->columns(1)
                    ->collapsed()
                    ->collapsible(),

                Section::make('Additional Information')
                    ->description('Metadata and product images')
                    ->schema([
                        KeyValue::make('meta_data')
                            ->label('Metadata')
                            ->keyLabel('Key')
                            ->valueLabel('Value')
                            ->reorderable(false),

                        Textarea::make('images')
                            ->label('Product Images (JSON)')
                            ->rows(4)
                            ->helperText('JSON array of product image URLs'),
                    ])
                    ->columns(1)

                    ->collapsible(),
            ]);
    }
}
