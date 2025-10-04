<?php

namespace App\Filament\Resources\ProductVariations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class ProductVariationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // TextColumn::make('product.name')
                // 	->searchable(),
                TextColumn::make('attributes.option'),

                // TextColumn::make('product_woocommerce_id')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('woocommerce_id')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('status')
                // 	->searchable(),
                // TextColumn::make('menu_order')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                TextColumn::make('price')
                    ->money('PLN')
                    ->sortable(),
                // TextColumn::make('regular_price')
                // 	->numeric()
                // 	->sortable(),
                // TextColumn::make('sale_price')
                // 	->numeric()
                // 	->sortable(),
                // TextColumn::make('date_on_sale_from')
                // 	->dateTime()
                // 	->sortable(),
                // TextColumn::make('date_on_sale_from_gmt')
                // 	->dateTime()
                // 	->sortable(),
                // TextColumn::make('date_on_sale_to')
                // 	->dateTime()
                // 	->sortable(),
                // TextColumn::make('date_on_sale_to_gmt')
                // ->dateTime()
                // ->sortable(),
                IconColumn::make('on_sale')
                    ->boolean(),
                IconColumn::make('purchasable')
                    ->boolean(),
                // IconColumn::make('virtual')
                // 	->boolean(),
                // IconColumn::make('downloadable')
                // 	->boolean(),
                // TextColumn::make('download_limit')
                // 	->numeric()
                // 	->sortable(),
                // TextColumn::make('download_expiry')
                // 	->numeric()
                // 	->sortable(),
                // TextColumn::make('tax_status')
                // 	->searchable(),
                // TextColumn::make('tax_class')
                // 	->searchable(),
                // IconColumn::make('manage_stock')
                // 	->boolean(),
                TextColumn::make('stock_quantity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('stock_status')
                    ->searchable(),
                // TextColumn::make('backorders')
                // 	->searchable(),
                // IconColumn::make('backordered')
                // 	->boolean(),
                IconColumn::make('visible')
                    ->boolean(),
                // IconColumn::make('shipping_required')
                // 	->boolean(),
                // IconColumn::make('shipping_taxable')
                // 	->boolean(),
                // TextColumn::make('weight')
                // 	->numeric()
                // 	->sortable(),
                // TextColumn::make('shipping_class')
                // 	->searchable(),
                // TextColumn::make('shipping_class_id')
                // 	->numeric()
                // 	->sortable(),
                // TextColumn::make('date_created')
                // 	->dateTime()
                // 	->sortable(),
                // TextColumn::make('date_created_gmt')
                // 	->dateTime()
                // 	->sortable(),
                // TextColumn::make('date_modified')
                // 	->dateTime()
                // 	->sortable(),
                // TextColumn::make('date_modified_gmt')
                // 	->dateTime()
                // 	->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            // ->defaultGroup('product.name')
            // ->groups([
            // 	Group::make('product.name')->titlePrefixedWithLabel(false)
            // 		->collapsible(),
            // ])->collapsedGroupsByDefault()->groupingSettingsHidden()

            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
