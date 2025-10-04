<?php

namespace App\Filament\Resources\Attributes\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AttributeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('woocommerce_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('type')
                    ->placeholder('-'),
                TextEntry::make('order_by')
                    ->placeholder('-'),
                IconEntry::make('has_archives')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
