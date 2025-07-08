<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'current_value',
        'location',
        'description',
        'property_type',
        'ownership_type',
        'images',
        'external_link',
        'property_tags',
    ];

    protected $casts = [
        'current_value' => 'decimal:2',
        'images' => 'array',
        'property_tags' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 