<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'woocommerce_id',
        'date_created',
        'date_created_gmt',
        'src',
        'name',
        'alt',
        'position',
    ];

    protected $casts = [
        'date_created' => 'datetime',
        'date_created_gmt' => 'datetime',
        'position' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
