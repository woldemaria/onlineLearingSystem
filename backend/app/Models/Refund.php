<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Refund extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'payment_id', 'order_id', 'amount', 'currency', 'reason',
        'status', 'provider', 'provider_refund_id',
        'failure_code', 'failure_message', 'requested_at',
        'processed_at', 'metadata', 'ip_address',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function payment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}