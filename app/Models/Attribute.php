<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Attribute extends Model
{
    //
    use HasTranslations;

    public array $translatable = ['name', 'slug'];

    protected $fillable = [
        'woocommerce_id',
        'name',
        'slug',
        'type',
        'order_by',
        'has_archives',
    ];

    protected $casts = [
        'woocommerce_id' => 'integer',
        'has_archives' => 'boolean',
    ];
}
