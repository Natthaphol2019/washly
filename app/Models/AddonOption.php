<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddonOption extends Model
{
    protected $fillable = [
        'code',
        'name',
        'image_path',
        'category',
        'unit_price',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];
}
