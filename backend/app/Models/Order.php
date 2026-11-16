<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid', 'user_id', 'organization_id', 'institution_id',
        'number', 'status', 'type', 'currency', 'subtotal',
        'discount_amount', 'tax_amount', 'total_amount',
        'billing_address', 'shipping_address', 'notes',
        'metadata', 'ip_address', 'user_agent',
        'placed_at', 'paid_at', 'completed_at', 'cancelled_at',
        'expires_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'billing_address' => 'array',
        'shipping_address' => 'array',
        'metadata' => 'array',
        'placed_at' => 'datetime',
        'paid_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $order) {
            if (empty($order->uuid)) {
                $order->uuid = (string) \Illuminate\Support\Str::uuid();
            }
            if (empty($order->number)) {
                $order->number = 'ORD-' . now()->format('ymdHis') . '-' . \Illuminate\Support\Str::random(4);
            }
        });
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function organization(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Payment::class, 'order_id');
    }

    public function refunds(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Refund::class, 'order_id');
    }
}