<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'woocommerce_id',
        'product_woocommerce_id',
        'permalink',
        'description',
        'status',
        'menu_order',
        'sku',
        'price',
        'regular_price',
        'sale_price',
        'date_on_sale_from',
        'date_on_sale_from_gmt',
        'date_on_sale_to',
        'date_on_sale_to_gmt',
        'on_sale',
        'purchasable',
        'virtual',
        'downloadable',
        'download_limit',
        'download_expiry',
        'tax_status',
        'tax_class',
        'manage_stock',
        'stock_quantity',
        'stock_status',
        'backorders',
        'backordered',
        'visible',
        'shipping_required',
        'shipping_taxable',
        'weight',
        'dimensions',
        'shipping_class',
        'shipping_class_id',
        'price_html',
        'image',
        'links',
        'meta_data_snapshot',
        'date_created',
        'date_created_gmt',
        'date_modified',
        'date_modified_gmt',
    ];

    protected $casts = [
        'product_woocommerce_id' => 'integer',
        'menu_order' => 'integer',
        'price' => 'decimal:4',
        'regular_price' => 'decimal:4',
        'sale_price' => 'decimal:4',
        'date_on_sale_from' => 'datetime',
        'date_on_sale_from_gmt' => 'datetime',
        'date_on_sale_to' => 'datetime',
        'date_on_sale_to_gmt' => 'datetime',
        'on_sale' => 'boolean',
        'purchasable' => 'boolean',
        'virtual' => 'boolean',
        'downloadable' => 'boolean',
        'download_limit' => 'integer',
        'download_expiry' => 'integer',
        'manage_stock' => 'boolean',
        'stock_quantity' => 'decimal:4',
        'backordered' => 'boolean',
        'visible' => 'boolean',
        'shipping_required' => 'boolean',
        'shipping_taxable' => 'boolean',
        'weight' => 'decimal:4',
        'dimensions' => 'array',
        'image' => 'array',
        'price_html' => 'string',
        'links' => 'array',
        'meta_data_snapshot' => 'array',
        'date_created' => 'datetime',
        'date_created_gmt' => 'datetime',
        'date_modified' => 'datetime',
        'date_modified_gmt' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(ProductVariationDownload::class);
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(ProductVariationAttribute::class);
    }

    public function metaData(): HasMany
    {
        return $this->hasMany(ProductVariationMeta::class);
    }

    public function orderLineItems(): HasMany
    {
        return $this->hasMany(OrderLineItem::class, 'variation_id', 'woocommerce_id');
    }
}
