<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'woocommerce_id',
        'email',
        'first_name',
        'last_name',
        'role',
        'username',
        'password',
        'billing',
        'shipping',
        'is_paying_customer',
        'avatar_url',
        'last_order_id',
        'last_order_number',
        'last_order_date',
        'last_order_date_gmt',
        'orders_count',
        'total_spent',
        'date_created',
        'date_created_gmt',
        'date_modified',
        'date_modified_gmt',
        'links',
    ];

    protected $casts = [
        'billing' => 'array',
        'shipping' => 'array',
        'is_paying_customer' => 'boolean',
        'last_order_date' => 'datetime',
        'last_order_date_gmt' => 'datetime',
        'orders_count' => 'integer',
        'total_spent' => 'decimal:4',
        'date_created' => 'datetime',
        'date_created_gmt' => 'datetime',
        'date_modified' => 'datetime',
        'date_modified_gmt' => 'datetime',
        'links' => 'array',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function lastOrder(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'last_order_id', 'woocommerce_id');
    }

    public function metaData(): HasMany
    {
        return $this->hasMany(CustomerMeta::class);
    }
}
