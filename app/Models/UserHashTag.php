<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserHashTag extends Model
{
    protected $guarded = [];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'hash_tag_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hashTag()
    {
        return $this->belongsTo(HashTag::class, 'hash_tag_id');
    }
}
