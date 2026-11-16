<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicYear extends Model
{
    use SoftDeletes;

    protected $fillable = ['institution_id', 'name', 'code', 'start_date', 'end_date', 'is_current', 'status', 'settings'];
    protected $casts = ['start_date' => 'date', 'end_date' => 'date', 'is_current' => 'boolean', 'status' => 'boolean', 'settings' => 'array'];

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function semesters(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Semester::class);
    }
}