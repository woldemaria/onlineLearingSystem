<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserProfile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'profileable_type', 'profileable_id',
        'legal_name', 'preferred_name', 'date_of_birth',
        'gender', 'nationality', 'bio', 'website',
        'public_portfolio', 'learning_goals', 'skills', 'interests',
        'education_background', 'professional_experience',
        'languages', 'social_links', 'visibility_settings',
        'verified_credentials_display',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'skills' => 'array',
        'interests' => 'array',
        'education_background' => 'array',
        'professional_experience' => 'array',
        'languages' => 'array',
        'social_links' => 'array',
        'visibility_settings' => 'array',
        'public_portfolio' => 'boolean',
        'verified_credentials_display' => 'boolean',
    ];
}