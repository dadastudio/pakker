<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'attribute_id',
        'name',
        'position',
        'visible',
        'variation',
        'options',
    ];

    protected $casts = [
        'attribute_id' => 'integer',
        'position' => 'integer',
        'visible' => 'boolean',
        'variation' => 'boolean',
        'options' => 'array',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
