<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderCouponLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'woocommerce_id',
        'code',
        'discount',
        'discount_tax',
        'meta_data',
    ];

    protected $casts = [
        'discount' => 'decimal:4',
        'discount_tax' => 'decimal:4',
        'meta_data' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
