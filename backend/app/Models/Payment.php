<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid', 'user_id', 'organization_id', 'institution_id',
        'order_id', 'type', 'status', 'provider', 'provider_payment_id',
        'provider_intent_id', 'provider_subscription_id',
        'amount', 'currency', 'amount_minor', 'fee_amount',
        'net_amount', 'tax_amount', 'description', 'metadata',
        'idempotency_key', 'captured', 'captured_at',
        'failure_code', 'failure_message', 'refunded',
        'refunded_at', 'refunded_by', 'refund_reason',
        'is_disputed', 'disputed_at', 'dispute_reason',
        'ip_address', 'user_agent', 'payment_method',
        'billing_details', 'shipping_details',
        'reconciled', 'reconciled_at', 'reconciled_by',
        'reconciliation_reference', 'expires_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'amount_minor' => 'integer',
        'metadata' => 'array',
        'captured' => 'boolean',
        'captured_at' => 'datetime',
        'refunded' => 'boolean',
        'refunded_at' => 'datetime',
        'is_disputed' => 'boolean',
        'disputed_at' => 'datetime',
        'billing_details' => 'array',
        'shipping_details' => 'array',
        'reconciled' => 'boolean',
        'reconciled_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $payment) {
            if (empty($payment->uuid)) {
                $payment->uuid = (string) \Illuminate\Support\Str::uuid();
            }
            if (empty($payment->idempotency_key)) {
                $payment->idempotency_key = (string) \Illuminate\Support\Str::uuid();
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

    public function refundedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'refunded_by');
    }

    public function reconciledBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'reconciled_by');
    }

    public function refunds(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Refund::class);
    }

    public function scopeCaptured($query)
    {
        return $query->where('status', 'succeeded')->where('captured', true);
    }
}