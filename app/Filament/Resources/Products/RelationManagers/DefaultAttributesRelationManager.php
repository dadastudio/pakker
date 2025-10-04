<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DefaultAttributesRelationManager extends RelationManager
{
    protected static string $relationship = 'defaultAttributes';

    protected static ?string $title = 'Default Attributes';

    protected static bool $isReadOnly = true;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('attribute_id')->label('Attribute ID')->sortable()->toggleable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('option')->label('Option')->wrap(),
            ])
            ->paginated(false);
    }
}
