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
    public function calculateDeliveryFee()
    {
        // ถ้ายอดเป็น null (แอดมินยังไม่เคยกรอกให้) คืนค่า null กลับไปก่อน
        if ($this->delivery_distance === null) {
            return null;
        }

        $distance = $this->delivery_distance;

        // ถ้าไม่เกิน 1.5 กิโลเมตร ส่งฟรี!
        if ($distance <= 1.5) {
            return 0;
        }
        return ceil($distance) * 20;
    }
    // ความสัมพันธ์: 1 User มีได้หลาย Order
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}