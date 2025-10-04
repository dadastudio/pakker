<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'woocommerce_id',
        'parent_id',
        'number',
        'order_key',
        'created_via',
        'version',
        'status',
        'currency',
        'date_created',
        'date_created_gmt',
        'date_modified',
        'date_modified_gmt',
        'discount_total',
        'discount_tax',
        'shipping_total',
        'shipping_tax',
        'cart_tax',
        'total',
        'total_tax',
        'prices_include_tax',
        'customer_woocommerce_id',
        'customer_id',
        'customer_ip_address',
        'customer_user_agent',
        'customer_note',
        'billing',
        'shipping',
        'payment_method',
        'payment_method_title',
        'transaction_id',
        'date_paid',
        'date_paid_gmt',
        'date_completed',
        'date_completed_gmt',
        'cart_hash',
        'set_paid',
        'links',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_created' => 'datetime',
        'date_created_gmt' => 'datetime',
        'date_modified' => 'datetime',
        'date_modified_gmt' => 'datetime',
        'discount_total' => 'decimal:4',
        'discount_tax' => 'decimal:4',
        'shipping_total' => 'decimal:4',
        'shipping_tax' => 'decimal:4',
        'cart_tax' => 'decimal:4',
        'total' => 'decimal:4',
        'total_tax' => 'decimal:4',
        'prices_include_tax' => 'boolean',
        'billing' => 'array',
        'shipping' => 'array',
        'date_paid' => 'datetime',
        'date_paid_gmt' => 'datetime',
        'date_completed' => 'datetime',
        'date_completed_gmt' => 'datetime',
        'set_paid' => 'boolean',
        'links' => 'array',
        'customer_woocommerce_id' => 'integer',
    ];

    /**
     * Customer associated to the order.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Parent order (for refunds or sub orders).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id', 'woocommerce_id');
    }

    /**
     * Child orders referencing this order as parent.
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'woocommerce_id');
    }

    /**
     * Line items for the order.
     */
    public function lineItems(): HasMany
    {
        return $this->hasMany(OrderLineItem::class);
    }

    /**
     * Tax lines tied to the order.
     */
    public function taxLines(): HasMany
    {
        return $this->hasMany(OrderTaxLine::class);
    }

    /**
     * Shipping lines applied to the order.
     */
    public function shippingLines(): HasMany
    {
        return $this->hasMany(OrderShippingLine::class);
    }

    /**
     * Fee lines applied to the order.
     */
    public function feeLines(): HasMany
    {
        return $this->hasMany(OrderFeeLine::class);
    }

    /**
     * Coupon lines applied to the order.
     */
    public function couponLines(): HasMany
    {
        return $this->hasMany(OrderCouponLine::class);
    }

    /**
     * Refunds linked to the order.
     */
    public function refunds(): HasMany
    {
        return $this->hasMany(OrderRefund::class);
    }

    /**
     * Arbitrary metadata associated with the order.
     */
    public function metaData(): HasMany
    {
        return $this->hasMany(OrderMeta::class);
    }
}
