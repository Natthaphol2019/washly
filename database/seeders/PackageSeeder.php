<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'ซัก-อบ 9-10 kg',
                'price' => 120.00,
                'description' => 'เหมาะสำหรับเสื้อผ้า 1-2 ตะกร้า (ประมาณ 20-30 ชิ้น)',
                'is_active' => true,
            ],
            [
                'name' => 'ซัก-อบ 13-15 kg',
                'price' => 160.00,
                'description' => 'เหมาะสำหรับเสื้อผ้า 3-4 ตะกร้า หรือผ้าปูที่นอนขนาด 5-6 ฟุต',
                'is_active' => true,
            ],
            [
                'name' => 'ซัก-อบ 18-20 kg',
                'price' => 220.00,
                'description' => 'เหมาะสำหรับครอบครัวใหญ่ หรือซักผ้านวมหนาขนาด 6 ฟุต',
                'is_active' => true,
            ],
            [
                'name' => 'ซัก-อบ-พับ 15 kg',
                'price' => 250.00,
                'description' => 'บริการซัก อบแห้ง พร้อมพับใส่ถุงเรียบร้อยพร้อมเก็บเข้าตู้',
                'is_active' => true,
            ]
        ];

        foreach ($packages as $pkg) {
            Package::updateOrCreate(['name' => $pkg['name']], $pkg);
        }
    }
}