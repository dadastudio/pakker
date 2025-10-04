<?php

namespace App\Filament\Resources\Orders\Resources\OrderLineItems\Pages;

use App\Filament\Resources\Orders\Resources\OrderLineItems\OrderLineItemResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOrderLineItem extends ViewRecord
{
    protected static string $resource = OrderLineItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
