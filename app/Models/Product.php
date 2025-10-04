<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Product extends Model implements HasMedia
{
	use HasFactory;
	use HasTranslations;
	use InteractsWithMedia;

	public array $translatable = ['name', 'slug', 'description', 'short_description'];

	protected $fillable = [
		'woocommerce_id',
		'name',
		'slug',
		'permalink',
		'type',
		'status',
		'featured',
		'catalog_visibility',
		'description',
		'short_description',
		'sku',
		'price',
		'regular_price',
		'sale_price',
		'date_on_sale_from',
		'date_on_sale_from_gmt',
		'date_on_sale_to',
		'date_on_sale_to_gmt',
		'on_sale',
		'total_sales',
		'virtual',
		'downloadable',
		'download_limit',
		'download_expiry',
		'external_url',
		'button_text',
		'tax_status',
		'tax_class',
		'manage_stock',
		'stock_quantity',
		'stock_status',
		'backorders',
		'backorders_allowed',
		'backordered',
		'low_stock_amount',
		'sold_individually',
		'weight',
		'dimensions',
		'shipping_required',
		'shipping_taxable',
		'shipping_class',
		'shipping_class_id',
		'reviews_allowed',
		'average_rating',
		'rating_count',
		'related_ids',
		'upsell_ids',
		'cross_sell_ids',
		'parent_id',
		'purchase_note',
		'variations',
		'grouped_products',
		'menu_order',
		'price_html',
		'has_options',
		'default_attributes_snapshot',
		'meta_data_snapshot',
		'links',
		'date_created',
		'date_created_gmt',
		'date_modified',
		'date_modified_gmt',
	];

	protected $casts = [
		'featured' => 'boolean',
		'on_sale' => 'boolean',
		'virtual' => 'boolean',
		'downloadable' => 'boolean',
		'manage_stock' => 'boolean',
		'backorders_allowed' => 'boolean',
		'backordered' => 'boolean',
		'sold_individually' => 'boolean',
		'shipping_required' => 'boolean',
		'shipping_taxable' => 'boolean',
		'reviews_allowed' => 'boolean',
		'has_options' => 'boolean',
		'price' => 'decimal:4',
		'regular_price' => 'decimal:4',
		'sale_price' => 'decimal:4',
		'stock_quantity' => 'decimal:4',
		'low_stock_amount' => 'decimal:4',
		'weight' => 'decimal:4',
		'average_rating' => 'decimal:4',
		'total_sales' => 'integer',
		'download_limit' => 'integer',
		'download_expiry' => 'integer',
		'rating_count' => 'integer',
		'menu_order' => 'integer',
		'dimensions' => 'array',
		'related_ids' => 'array',
		'upsell_ids' => 'array',
		'cross_sell_ids' => 'array',
		'variations' => 'array',
		'grouped_products' => 'array',
		'default_attributes_snapshot' => 'array',
		'meta_data_snapshot' => 'array',
		'links' => 'array',
		'date_on_sale_from' => 'datetime',
		'date_on_sale_from_gmt' => 'datetime',
		'date_on_sale_to' => 'datetime',
		'date_on_sale_to_gmt' => 'datetime',
		'date_created' => 'datetime',
		'date_created_gmt' => 'datetime',
		'date_modified' => 'datetime',
		'date_modified_gmt' => 'datetime',
	];

	public function images(): HasMany
	{
		return $this->hasMany(ProductImage::class);
	}

	public function downloads(): HasMany
	{
		return $this->hasMany(ProductDownload::class);
	}

	public function attributes(): HasMany
	{
		return $this->hasMany(ProductAttribute::class);
	}

	public function defaultAttributes(): HasMany
	{
		return $this->hasMany(ProductDefaultAttribute::class);
	}

	public function categories(): HasMany
	{
		return $this->hasMany(ProductCategory::class);
	}

	public function tags(): HasMany
	{
		return $this->hasMany(ProductTag::class);
	}

	public function metaData(): HasMany
	{
		return $this->hasMany(ProductMeta::class);
	}

	public function variations(): HasMany
	{
		return $this->hasMany(ProductVariation::class);
	}

	public function parent(): BelongsTo
	{
		return $this->belongsTo(self::class, 'parent_id', 'id');
	}

	public function children(): HasMany
	{
		return $this->hasMany(self::class, 'parent_id', 'id');
	}

	public function orderLineItems(): HasMany
	{
		return $this->hasMany(OrderLineItem::class, 'product_id', 'id');
	}

	public function registerMediaCollections(): void
	{
		$this->addMediaCollection('images')
			->useFallbackUrl('/images/product-placeholder.png')
			->useFallbackPath(public_path('/images/product-placeholder.png'));

		$this->addMediaCollection('gallery')
			->useFallbackUrl('/images/product-placeholder.png')
			->useFallbackPath(public_path('/images/product-placeholder.png'));
	}

	public function registerMediaConversions(?Media $media = null): void
	{
		$this->addMediaConversion('thumb')
			->width(150)
			->height(150)
			->sharpen(10);

		$this->addMediaConversion('preview')
			->width(300)
			->height(300)
			->nonQueued();
	}
}
