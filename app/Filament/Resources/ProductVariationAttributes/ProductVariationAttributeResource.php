<?php

namespace App\Filament\Resources\ProductVariationAttributes;

use App\Filament\Resources\ProductVariationAttributes\Pages\CreateProductVariationAttribute;
use App\Filament\Resources\ProductVariationAttributes\Pages\EditProductVariationAttribute;
use App\Filament\Resources\ProductVariationAttributes\Pages\ListProductVariationAttributes;
use App\Filament\Resources\ProductVariationAttributes\Schemas\ProductVariationAttributeForm;
use App\Filament\Resources\ProductVariationAttributes\Tables\ProductVariationAttributesTable;
use App\Models\ProductVariationAttribute;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductVariationAttributeResource extends Resource
{
    protected static ?string $model = ProductVariationAttribute::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ProductVariationAttributeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductVariationAttributesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductVariationAttributes::route('/'),
            'create' => CreateProductVariationAttribute::route('/create'),
            'edit' => EditProductVariationAttribute::route('/{record}/edit'),
        ];
    }
}
