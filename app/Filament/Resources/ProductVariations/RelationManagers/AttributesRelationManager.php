<?php

namespace App\Filament\Resources\ProductVariations\RelationManagers;

use App\Filament\Resources\ProductVariationAttributes\ProductVariationAttributeResource;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AttributesRelationManager extends RelationManager
{
    protected static string $relationship = 'attributes';

    // protected static ?string $relatedResource = ProductVariationAttributeResource::class;
    protected static ?string $title = 'Attributes';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('name')->required(),
            TextInput::make('option')->required(),

        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('option'),

            ])

            ->recordActions([
                EditAction::make(),
            ]);

        // return $table
        // 	->headerActions([
        // 		CreateAction::make(),
        // 	]);
    }
}
