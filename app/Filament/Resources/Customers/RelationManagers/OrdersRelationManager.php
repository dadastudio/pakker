<?php

namespace App\Filament\Resources\Customers\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('woocommerce_id')
                    ->required()
                    ->numeric(),
                Select::make('parent_id')
                    ->relationship('parent', 'id'),
                TextInput::make('number'),
                TextInput::make('order_key'),
                TextInput::make('created_via'),
                TextInput::make('version'),
                TextInput::make('status'),
                TextInput::make('currency'),
                DateTimePicker::make('date_created'),
                DateTimePicker::make('date_created_gmt'),
                DateTimePicker::make('date_modified'),
                DateTimePicker::make('date_modified_gmt'),
                TextInput::make('discount_total')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('discount_tax')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('shipping_total')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('shipping_tax')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('cart_tax')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('total_tax')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Toggle::make('prices_include_tax')
                    ->required(),
                TextInput::make('customer_woocommerce_id')
                    ->numeric(),
                TextInput::make('customer_ip_address'),
                TextInput::make('customer_user_agent'),
                Textarea::make('customer_note')
                    ->columnSpanFull(),
                TextInput::make('billing'),
                TextInput::make('shipping'),
                TextInput::make('payment_method'),
                TextInput::make('payment_method_title'),
                TextInput::make('transaction_id'),
                DateTimePicker::make('date_paid'),
                DateTimePicker::make('date_paid_gmt'),
                DateTimePicker::make('date_completed'),
                DateTimePicker::make('date_completed_gmt'),
                TextInput::make('cart_hash'),
                Toggle::make('set_paid')
                    ->required(),
                TextInput::make('links'),
            ]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('woocommerce_id')
                    ->numeric(),
                TextEntry::make('parent.id')
                    ->label('Parent')
                    ->placeholder('-'),
                TextEntry::make('number')
                    ->placeholder('-'),
                TextEntry::make('order_key')
                    ->placeholder('-'),
                TextEntry::make('created_via')
                    ->placeholder('-'),
                TextEntry::make('version')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->placeholder('-'),
                TextEntry::make('currency')
                    ->placeholder('-'),
                TextEntry::make('date_created')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('date_created_gmt')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('date_modified')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('date_modified_gmt')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('discount_total')
                    ->numeric(),
                TextEntry::make('discount_tax')
                    ->numeric(),
                TextEntry::make('shipping_total')
                    ->numeric(),
                TextEntry::make('shipping_tax')
                    ->numeric(),
                TextEntry::make('cart_tax')
                    ->numeric(),
                TextEntry::make('total')
                    ->numeric(),
                TextEntry::make('total_tax')
                    ->numeric(),
                IconEntry::make('prices_include_tax')
                    ->boolean(),
                TextEntry::make('customer_woocommerce_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('customer_ip_address')
                    ->placeholder('-'),
                TextEntry::make('customer_user_agent')
                    ->placeholder('-'),
                TextEntry::make('customer_note')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('payment_method')
                    ->placeholder('-'),
                TextEntry::make('payment_method_title')
                    ->placeholder('-'),
                TextEntry::make('transaction_id')
                    ->placeholder('-'),
                TextEntry::make('date_paid')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('date_paid_gmt')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('date_completed')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('date_completed_gmt')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('cart_hash')
                    ->placeholder('-'),
                IconEntry::make('set_paid')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('woocommerce_id')
            ->columns([
                TextColumn::make('woocommerce_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('parent.id')
                    ->searchable(),
                TextColumn::make('number')
                    ->searchable(),
                TextColumn::make('order_key')
                    ->searchable(),
                TextColumn::make('created_via')
                    ->searchable(),
                TextColumn::make('version')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('currency')
                    ->searchable(),
                TextColumn::make('date_created')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('date_created_gmt')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('date_modified')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('date_modified_gmt')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('discount_total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('discount_tax')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('shipping_total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('shipping_tax')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('cart_tax')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_tax')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('prices_include_tax')
                    ->boolean(),
                TextColumn::make('customer_woocommerce_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('customer_ip_address')
                    ->searchable(),
                TextColumn::make('customer_user_agent')
                    ->searchable(),
                TextColumn::make('payment_method')
                    ->searchable(),
                TextColumn::make('payment_method_title')
                    ->searchable(),
                TextColumn::make('transaction_id')
                    ->searchable(),
                TextColumn::make('date_paid')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('date_paid_gmt')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('date_completed')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('date_completed_gmt')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('cart_hash')
                    ->searchable(),
                IconColumn::make('set_paid')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                AssociateAction::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
