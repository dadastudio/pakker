<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use Filament\Forms\Components\Field;
use Illuminate\Support\Str;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\RichEditor;

class ProductForm
{
	public static function configure(Schema $schema): Schema
	{
		return $schema
			->components([
				Section::make('General')
					->schema([
						// TextInput::make('name')->required()->maxLength(255)->translatableTabs(),
						// TextInput::make('slug')->maxLength(255),

						Select::make('parent_id')
							->relationship('parent', 'name', ignoreRecord: true)

							->label('Parent'),

						// TextInput::make('permalink')->maxLength(500),
						// Select::make('type')
						// 	->options([
						// 		'simple' => 'Simple',
						// 		'grouped' => 'Grouped',
						// 		'external' => 'External',
						// 		'variable' => 'Variable',
						// 		'variation' => 'Variation',
						// 	])
						// 	->required(),
						Select::make('status')
							->options([
								'draft' => 'Draft',
								'pending' => 'Pending',
								'private' => 'Private',
								'publish' => 'Published',
							])
							->required(),
					])->columns(1),


				TranslatableTabs::make('Translations')
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
						TextInput::make('name')->required()->maxLength(255),
						TextInput::make('slug')->required()->maxLength(255),

						RichEditor::make('short_description')
							->label('Short Description')

						,
						RichEditor::make('description')
							->label('Description')

						,
					]),


				Section::make('Pricing')
					->schema([
						TextInput::make('price')->numeric()->label('Price'),
						TextInput::make('regular_price')->numeric()->label('Regular Price'),
						TextInput::make('sale_price')->numeric()->label('Sale Price'),
						Toggle::make('on_sale')->label('On Sale'),
						DateTimePicker::make('date_on_sale_from')->label('Sale From'),
						DateTimePicker::make('date_on_sale_to')->label('Sale To'),
					])->columns(3),

				Section::make('Inventory')
					->schema([
						TextInput::make('sku')->label('SKU')->maxLength(100),
						Toggle::make('manage_stock')->label('Manage Stock'),
						TextInput::make('stock_quantity')->numeric()->label('Stock Quantity'),
						Select::make('stock_status')
							->options([
								'instock' => 'In Stock',
								'outofstock' => 'Out of Stock',
								'onbackorder' => 'On Backorder',
							])
							->label('Stock Status'),
						TextInput::make('backorders')->maxLength(50)->label('Backorders'),
						Toggle::make('backorders_allowed')->label('Backorders Allowed'),
						TextInput::make('low_stock_amount')->numeric()->label('Low Stock Amount'),
					])->columns(3),

				Section::make('Flags')
					->schema([
						Toggle::make('featured')->label('Featured'),
						// Toggle::make('virtual')->label('Virtual'),
						// Toggle::make('downloadable')->label('Downloadable'),
						// Toggle::make('sold_individually')->label('Sold Individually'),
						// Toggle::make('shipping_required')->label('Shipping Required'),
						// Toggle::make('shipping_taxable')->label('Shipping Taxable'),
						// Toggle::make('reviews_allowed')->label('Reviews Allowed'),
					])->columns(4),

				// Section::make('Dimensions & Weight')
				// 	->schema([
				// 		TextInput::make('weight')->numeric()->label('Weight'),
				// 		KeyValue::make('dimensions')
				// 			->label('Dimensions')
				// 			->columnSpanFull(),
				// 	])->columns(2),

				Section::make('Media')
					->schema([
						SpatieMediaLibraryFileUpload::make('images')
							->collection('images')
							->image()
							->multiple()
							->reorderable()
							->maxFiles(10)
							->columnSpanFull(),
						SpatieMediaLibraryFileUpload::make('gallery')
							->collection('gallery')
							->image()
							->multiple()
							->reorderable()
							->columnSpanFull(),
					]),
			])->columns(1);
	}
}
