<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductsTable
{
	public static function configure(Table $table): Table
	{
		return $table
			->columns([
				SpatieMediaLibraryImageColumn::make('images')
					->collection('images')
					->conversion('thumb')->stacked()->limit(3)->circular()
					->label('Image'),
				TextColumn::make('id')->sortable()->searchable(),
				// TextColumn::make('parent_id')->sortable()->searchable(),
				TextColumn::make('woocommerce_id')->sortable()->searchable(),
				TextColumn::make('name')->sortable()->searchable(),
				// TextColumn::make('permalink')->sortable()->searchable(),
				// TextColumn::make('slug')->sortable()->searchable(),
				// TextColumn::make('sku')->label('SKU')->sortable()->searchable(),
				// TextColumn::make('type')->badge()->sortable(),

				TextColumn::make('status')
					->badge()
					->color(fn(string $state): string => match ($state) {
						'publish' => 'success',
						'draft' => 'info',
						'pending' => 'warning',
						'private' => 'info',
						'trash' => 'danger',
						default => 'gray',
					})
					->sortable(),

				TextColumn::make('price')->label('Price')->formatStateUsing(fn($state) => static::formatMoney($state))->sortable(),
				TextColumn::make('regular_price')
					->label('Regular price')
					->formatStateUsing(fn($state) => static::formatMoney($state))
					->toggleable(isToggledHiddenByDefault: true),
				TextColumn::make('sale_price')
					->label('Sale price')
					->formatStateUsing(fn($state) => static::formatMoney($state))
					->toggleable(isToggledHiddenByDefault: true),
				IconColumn::make('on_sale')->label('On sale')->boolean(),
				// IconColumn::make('featured')->boolean(),
				IconColumn::make('manage_stock')->label('Manages stock')->boolean(),
				TextColumn::make('stock_status')
					->label('Stock status')
					->badge()
					->color(fn(string $state): string => match ($state) {
						'instock' => 'success',
						'outofstock' => 'danger',
						'onbackorder' => 'warning',
						default => 'gray',
					}),
				TextColumn::make('stock_quantity')->label('Stock qty')->numeric(0)->toggleable(isToggledHiddenByDefault: true),
				TextColumn::make('total_sales')->label('Sales')->numeric()->toggleable(isToggledHiddenByDefault: true),
				TextColumn::make('variations_count')->counts('variations')->label('Variations')->sortable()->toggleable(isToggledHiddenByDefault: true),
				// TextColumn::make('date_modified')->label('Modified')->dateTime()->sortable(),
			])
			->filters([
				Filter::make('without_parent')
					->label('Without parent')
					->query(fn(Builder $query): Builder => $query->where(function (Builder $query) {
						return $query->whereNull('parent_id')->orWhere('parent_id', 0);
					}))
					->default(),

			])
			->recordActions([
				ViewAction::make(),
				EditAction::make(),
			])
			->toolbarActions([
				BulkActionGroup::make([
					DeleteBulkAction::make(),
				]),
			]);
	}

	protected static function formatMoney(mixed $value, bool $blankForNull = false): string
	{
		if ($value === null || $value === '') {
			return $blankForNull ? '—' : number_format(0.0, 2);
		}

		return number_format((float) $value, 2);
	}
}
