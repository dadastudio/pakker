<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderRefund extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'woocommerce_id',
        'total',
        'reason',
        'refunded_by',
        'meta_data',
    ];

    protected $casts = [
        'total' => 'decimal:4',
        'meta_data' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function refunder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'refunded_by');
    }
}
