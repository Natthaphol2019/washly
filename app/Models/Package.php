<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = ['name', 'price', 'description', 'image_path', 'default_detergent_code', 'is_active'];
    protected $casts = [
        'is_active' => 'boolean',
    ];
}
