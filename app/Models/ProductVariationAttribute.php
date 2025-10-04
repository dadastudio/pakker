<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariationAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_variation_id',
        'attribute_id',
        'name',
        'option',
    ];

    protected $casts = [
        'attribute_id' => 'integer',
    ];

    public function variation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class, 'product_variation_id');
    }
}
