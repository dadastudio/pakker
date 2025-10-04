<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use App\Filament\Resources\Orders\Resources\OrderLineItems\OrderLineItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class LineItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'lineItems';

    protected static ?string $relatedResource = OrderLineItemResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
