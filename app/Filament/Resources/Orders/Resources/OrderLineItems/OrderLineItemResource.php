<?php

namespace App\Filament\Resources\Orders\Resources\OrderLineItems;

use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Resources\Orders\Resources\OrderLineItems\Pages\CreateOrderLineItem;
use App\Filament\Resources\Orders\Resources\OrderLineItems\Pages\EditOrderLineItem;
use App\Filament\Resources\Orders\Resources\OrderLineItems\Pages\ViewOrderLineItem;
use App\Filament\Resources\Orders\Resources\OrderLineItems\Schemas\OrderLineItemForm;
use App\Filament\Resources\Orders\Resources\OrderLineItems\Schemas\OrderLineItemInfolist;
use App\Filament\Resources\Orders\Resources\OrderLineItems\Tables\OrderLineItemsTable;
use App\Models\OrderLineItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OrderLineItemResource extends Resource
{
    protected static ?string $model = OrderLineItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = OrderResource::class;

    public static function form(Schema $schema): Schema
    {
        return OrderLineItemForm::configure($schema)->columns(1);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OrderLineItemInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrderLineItemsTable::configure($table);
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
            'create' => CreateOrderLineItem::route('/create'),
            'view' => ViewOrderLineItem::route('/{record}'),
            'edit' => EditOrderLineItem::route('/{record}/edit'),
        ];
    }
}
