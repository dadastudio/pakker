<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use JsonException;

class ProductMeta extends Model
{
    use HasFactory;

    protected $table = 'product_meta';

    protected $fillable = [
        'product_id',
        'woocommerce_id',
        'meta_key',
        'meta_value',
        'display_key',
        'display_value',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected function metaValue(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $this->decodeJson($value),
            set: fn ($value) => $this->encodeJson($value),
        );
    }

    protected function displayValue(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $this->decodeJson($value),
            set: fn ($value) => $this->encodeJson($value),
        );
    }

    private function decodeJson(?string $value)
    {
        if ($value === null) {
            return null;
        }

        $decoded = json_decode($value, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : $value;
    }

    private function encodeJson($value): ?string
    {
        if ($value === null || $value === '') {
            return $value;
        }

        if (is_string($value)) {
            return $value;
        }

        try {
            return json_encode($value, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return (string) $value;
        }
    }
}
