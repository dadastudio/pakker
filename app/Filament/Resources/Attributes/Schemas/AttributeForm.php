<?php

namespace App\Filament\Resources\Attributes\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class AttributeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('woocommerce_id')
                    ->numeric(),

                TranslatableTabs::make('anyLabel')
                    ->modifyFieldsUsing(function (Field $component, string $locale) {
                        if ($component->getName() === "name.{$locale}") {
                            $component
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, callable $set) use ($locale) {
                                    $set("slug.{$locale}", Str::slug($state));
                                });
                        }
                    })
                    ->schema([
                        TextInput::make('name')->required(),
                        TextInput::make('slug')->required(),
                    ]),

                TextInput::make('type'),
                TextInput::make('order_by'),
                // Toggle::make('has_archives')
                // 	->required(),
            ])->columns(1);
    }
}
