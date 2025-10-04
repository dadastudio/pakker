<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderTaxLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'woocommerce_id',
        'rate_code',
        'rate_id',
        'label',
        'compound',
        'tax_total',
        'shipping_tax_total',
        'meta_data',
    ];

    protected $casts = [
        'compound' => 'boolean',
        'tax_total' => 'decimal:4',
        'shipping_tax_total' => 'decimal:4',
        'meta_data' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
