<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'line_id',    // เพิ่มบรรทัดนี้
        'fullname',
        'username',
        'avatar',     // เพิ่มบรรทัดนี้
        'phone',
        'address',
        'latitude',
        'longitude',
        'map_link',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // ความสัมพันธ์: 1 User มีได้หลาย Order
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}