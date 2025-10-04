<?php

namespace App\Filament\Resources\Orders\Resources\OrderLineItems\Pages;

use App\Filament\Resources\Orders\Resources\OrderLineItems\OrderLineItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrderLineItem extends CreateRecord
{
    protected static string $resource = OrderLineItemResource::class;
}
