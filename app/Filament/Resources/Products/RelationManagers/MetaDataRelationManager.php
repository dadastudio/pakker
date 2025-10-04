<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MetaDataRelationManager extends RelationManager
{
    protected static string $relationship = 'metaData';

    protected static ?string $title = 'Meta Data';

    protected static bool $isReadOnly = true;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('meta_key')->label('Key')->searchable(),
                TextColumn::make('display_key')->label('Display key')->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('meta_value')
                    ->label('Value')
                    ->formatStateUsing(fn ($state) => $this->stringify($state))
                    ->wrap(),
                TextColumn::make('display_value')
                    ->label('Display value')
                    ->formatStateUsing(fn ($state) => $this->stringify($state))
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->paginated(false);
    }

    private function stringify(mixed $state): string
    {
        if ($state === null || $state === '') {
            return '—';
        }

        if (is_array($state)) {
            return json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }

        return (string) $state;
    }
}
