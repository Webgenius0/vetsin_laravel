<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserImages extends Model
{
    protected $guarded = [];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
