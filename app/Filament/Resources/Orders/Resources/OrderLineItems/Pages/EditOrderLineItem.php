<?php

namespace App\Filament\Resources\Orders\Resources\OrderLineItems\Pages;

use App\Filament\Resources\Orders\Resources\OrderLineItems\OrderLineItemResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditOrderLineItem extends EditRecord
{
    protected static string $resource = OrderLineItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
