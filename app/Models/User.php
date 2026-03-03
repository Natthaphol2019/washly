<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'fullname',   // อัปเดตตามที่แก้ใน Migration
        'username',
        'password',
        'phone',
        'address',
        'latitude',   // เพิ่มฟิลด์แผนที่
        'longitude',  // เพิ่มฟิลด์แผนที่
        'map_link',   // เพิ่มฟิลด์ลิงก์แผนที่
        'role',
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
    public function orders() {
        return $this->hasMany(Order::class);
    }
}