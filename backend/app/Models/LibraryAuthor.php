<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LibraryAuthor extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'slug', 'bio', 'nationality', 'birth_date', 'death_date', 'website', 'photo_path', 'metadata'];
    protected $casts = ['birth_date' => 'date', 'death_date' => 'date', 'metadata' => 'array'];

    public function resources(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LibraryResource::class, 'author_id');
    }
}