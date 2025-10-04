<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderFeeLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'woocommerce_id',
        'name',
        'tax_class',
        'tax_status',
        'total',
        'total_tax',
        'taxes',
        'meta_data',
    ];

    protected $casts = [
        'total' => 'decimal:4',
        'total_tax' => 'decimal:4',
        'taxes' => 'array',
        'meta_data' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
