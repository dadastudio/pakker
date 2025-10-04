<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DownloadsRelationManager extends RelationManager
{
    protected static string $relationship = 'downloads';

    protected static ?string $title = 'Downloads';

    protected static bool $isReadOnly = true;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('woocommerce_id')->label('Woo ID')->sortable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('file')->label('File')->copyable()->wrap(),
            ])
            ->paginated(false);
    }
}
