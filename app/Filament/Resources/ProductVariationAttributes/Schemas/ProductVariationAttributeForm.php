<?php

namespace App\Filament\Resources\ProductVariationAttributes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductVariationAttributeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('product_variation_id')
                    ->required()
                    ->numeric(),
                TextInput::make('attribute_id')
                    ->numeric(),
                TextInput::make('name'),
                TextInput::make('option'),
            ]);
    }
}
