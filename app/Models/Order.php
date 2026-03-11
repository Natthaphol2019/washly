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
        'distance',
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
        'pickup_latitude' => 'float',
        'pickup_longitude' => 'float',
        'distance' => 'float',
    ];

    public function getStraightLineDistanceKmAttribute(): ?float
    {
        if ($this->pickup_latitude === null || $this->pickup_longitude === null) {
            return null;
        }

        $shopCoordinates = app(\App\Services\DeliveryDistanceService::class)->shopCoordinates();

        if ($shopCoordinates === null) {
            return null;
        }

        return round(
            $this->calculateHaversineDistance(
                (float) $this->pickup_latitude,
                (float) $this->pickup_longitude,
                (float) $shopCoordinates['latitude'],
                (float) $shopCoordinates['longitude']
            ),
            2
        );
    }

    // ผูกความสัมพันธ์กับตารางอื่นๆ
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class);
    }

    public function logs()
    {
        return $this->hasMany(OrderLog::class);
    }

    private function calculateHaversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371;
        $latDelta = deg2rad($lat1 - $lat2);
        $lngDelta = deg2rad($lon1 - $lon2);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos(deg2rad($lat2)) * cos(deg2rad($lat1)) *
            sin($lngDelta / 2) * sin($lngDelta / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}