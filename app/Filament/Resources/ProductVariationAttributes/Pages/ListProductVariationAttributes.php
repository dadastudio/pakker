<?php

namespace App\Filament\Resources\ProductVariationAttributes\Pages;

use App\Filament\Resources\ProductVariationAttributes\ProductVariationAttributeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductVariationAttributes extends ListRecords
{
    protected static string $resource = ProductVariationAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
