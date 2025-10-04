<?php

namespace App\Filament\Resources\ProductVariationAttributes\Pages;

use App\Filament\Resources\ProductVariationAttributes\ProductVariationAttributeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductVariationAttribute extends CreateRecord
{
    protected static string $resource = ProductVariationAttributeResource::class;
}
