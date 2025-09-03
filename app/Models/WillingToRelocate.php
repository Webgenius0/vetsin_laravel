<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WillingToRelocate extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'status',
    ];
     protected $hidden = [
        'status',
        'created_at',
        'updated_at',
    ];
}
