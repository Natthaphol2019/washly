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
        'use_customer_detergent',
        'use_customer_softener',
        'wash_temp',
        'dry_temp',
        'status',
        'payment_status',
        'payment_method',
        'subtotal',
        'addon_total',
        'selected_addons',
        'total_price',
    ];

    protected $casts = [
        'selected_addons' => 'array',
        'use_customer_detergent' => 'boolean',
        'use_customer_softener' => 'boolean',
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