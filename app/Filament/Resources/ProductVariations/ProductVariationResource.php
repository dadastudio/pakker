<?php

namespace App\Filament\Resources\ProductVariations;

use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\ProductVariations\Pages\CreateProductVariation;
use App\Filament\Resources\ProductVariations\Pages\EditProductVariation;
use App\Filament\Resources\ProductVariations\Pages\ListProductVariations;
use App\Filament\Resources\ProductVariations\Pages\ViewProductVariation;
use App\Filament\Resources\ProductVariations\RelationManagers\AttributesRelationManager;
use App\Filament\Resources\ProductVariations\Schemas\ProductVariationForm;
use App\Filament\Resources\ProductVariations\Schemas\ProductVariationInfolist;
use App\Filament\Resources\ProductVariations\Tables\ProductVariationsTable;
use App\Models\ProductVariation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductVariationResource extends Resource
{
    protected static ?string $model = ProductVariation::class;

    protected static ?string $parentResource = ProductResource::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return ProductVariationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProductVariationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductVariationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
            AttributesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductVariations::route('/'),
            'create' => CreateProductVariation::route('/create'),
            'view' => ViewProductVariation::route('/{record}'),
            'edit' => EditProductVariation::route('/{record}/edit'),
        ];
    }
}
