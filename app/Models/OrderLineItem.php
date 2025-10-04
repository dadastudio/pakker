<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderLineItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'woocommerce_id',
        'name',
        'product_id',
        'woocommerce_product_id',
        'variation_id',
        'woocommerce_variation_id',
        'quantity',
        'tax_class',
        'subtotal',
        'subtotal_tax',
        'total',
        'total_tax',
        'taxes',
        'meta_data',
        'sku',
        'price',
        'parent_name',
        'images',
        'date_created',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'product_id' => 'integer',
        'woocommerce_product_id' => 'integer',
        'variation_id' => 'integer',
        'woocommerce_variation_id' => 'integer',
        'subtotal' => 'decimal:4',
        'subtotal_tax' => 'decimal:4',
        'total' => 'decimal:4',
        'total_tax' => 'decimal:4',
        'price' => 'decimal:4',
        'taxes' => 'array',
        'meta_data' => 'array',
        'images' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class);
    }
}
