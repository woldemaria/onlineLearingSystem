<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'product_id', 'order_id', 'status',
        'provider', 'provider_subscription_id',
        'starts_at', 'ends_at', 'trial_ends_at', 'cancelled_at',
        'renewal_at', 'renewal_count', 'currency',
        'amount', 'metadata',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'renewal_at' => 'datetime',
        'renewal_count' => 'integer',
        'amount' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->whereNull('cancelled_at')
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>', now());
            });
    }
}