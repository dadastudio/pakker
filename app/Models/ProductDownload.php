<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDownload extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'woocommerce_id',
        'name',
        'file',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
