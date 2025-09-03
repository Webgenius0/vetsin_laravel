<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdealConnection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'info',
        'status',
    ];
     protected $hidden = [
        'status',
        'created_at',
        'updated_at',
    ];
}
