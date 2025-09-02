<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HashTag extends Model
{
    protected $guarded = [];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'status'
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_tags', 'tag_id', 'user_id');
    }
}
