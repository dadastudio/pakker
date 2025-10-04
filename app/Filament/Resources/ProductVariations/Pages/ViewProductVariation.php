<?php

namespace App\Filament\Resources\ProductVariations\Pages;

use App\Filament\Resources\ProductVariations\ProductVariationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProductVariation extends ViewRecord
{
    protected static string $resource = ProductVariationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
