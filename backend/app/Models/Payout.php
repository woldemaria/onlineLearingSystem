<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payout extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'instructor_id', 'payout_number', 'status', 'method',
        'gross_amount', 'platform_fee', 'tax_deducted', 'net_amount',
        'currency', 'bank_details', 'metadata', 'requested_at',
        'processed_at', 'completed_at', 'failure_reason',
        'provider_payout_id', 'reconciled', 'reconciled_at',
        'reconciled_by', 'fiscal_year', 'fiscal_month',
        'invoice_path', 'notes',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'tax_deducted' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'bank_details' => 'array',
        'metadata' => 'array',
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
        'reconciled' => 'boolean',
        'reconciled_at' => 'datetime',
        'fiscal_year' => 'integer',
        'fiscal_month' => 'integer',
    ];

    public function instructor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    public function reconciledBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'reconciled_by');
    }

    public function earnings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InstructorEarning::class, 'payout_id');
    }
}