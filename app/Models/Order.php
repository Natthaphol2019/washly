<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'package_id',
        'time_slot_id',
        'pickup_address',
        'pickup_latitude',  // เพิ่มฟิลด์แผนที่จุดรับผ้า
        'pickup_longitude', // เพิ่มฟิลด์แผนที่จุดรับผ้า
        'pickup_map_link',  // เพิ่มฟิลด์ลิงก์จุดรับผ้า
        'wash_temp',
        'dry_temp',
        'status',
        'payment_status',
        'total_price',
    ];

    // ผูกความสัมพันธ์กับตารางอื่นๆ
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function package() {
        return $this->belongsTo(Package::class);
    }

    public function timeSlot() {
        return $this->belongsTo(TimeSlot::class);
    }

    public function logs() {
        return $this->hasMany(OrderLog::class);
    }
}