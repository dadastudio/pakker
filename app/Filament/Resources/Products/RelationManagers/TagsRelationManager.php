<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TagsRelationManager extends RelationManager
{
    protected static string $relationship = 'tags';

    protected static ?string $title = 'Tags';

    protected static bool $isReadOnly = true;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('term_id')->label('Term ID')->sortable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('slug')->searchable(),
            ])
            ->paginated(false);
    }
}
