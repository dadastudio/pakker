<?php

namespace App\Filament\Resources\ProductVariationAttributes\Pages;

use App\Filament\Resources\ProductVariationAttributes\ProductVariationAttributeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProductVariationAttribute extends EditRecord
{
    protected static string $resource = ProductVariationAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
