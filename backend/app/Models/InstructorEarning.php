<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstructorEarning extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'instructor_id', 'course_id', 'order_item_id', 'payment_id',
        'transaction_id', 'type', 'status', 'gross_amount',
        'platform_fee', 'tax_deducted', 'net_amount',
        'currency', 'description', 'metadata',
        'earned_at', 'paid_at', 'payout_id', 'payout_reference',
        'fiscal_year', 'fiscal_month', 'invoice_number',
        'invoice_path', 'is_invoiced', 'is_paid',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'tax_deducted' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'metadata' => 'array',
        'earned_at' => 'datetime',
        'paid_at' => 'datetime',
        'is_invoiced' => 'boolean',
        'is_paid' => 'boolean',
    ];

    public function instructor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function orderItem(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }

    public function payment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
}