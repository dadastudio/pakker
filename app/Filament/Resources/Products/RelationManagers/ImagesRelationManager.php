<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    protected static ?string $title = 'Images';

    protected static bool $isReadOnly = true;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('position')->numeric()->sortable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('alt')->label('Alt text')->searchable(),
                TextColumn::make('src')->label('URL')->copyable()->wrap(),
            ])
            ->defaultSort('position')
            ->paginated(false);
    }
}
