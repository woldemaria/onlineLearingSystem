<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LibraryCategory extends Model
{
    use SoftDeletes;

    protected $fillable = ['parent_id', 'name', 'slug', 'description', 'status', 'settings'];
    protected $casts = ['status' => 'boolean', 'settings' => 'array'];

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LibraryCategory::class, 'parent_id');
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LibraryCategory::class, 'parent_id');
    }

    public function resources(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LibraryResource::class, 'category_id');
    }
}