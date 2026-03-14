<?php

namespace Database\Seeders;

use App\Models\AddonOption;
use Illuminate\Database\Seeder;

class AddonOptionSeeder extends Seeder
{
    public function run(): void
    {
        $addons = [
            [
                'code' => 'detergent_standard',
                'name' => 'น้ำยาซักผ้า สูตรมาตรฐาน (ทำความสะอาดล้ำลึก)',
                'category' => 'detergent',
                'unit_price' => 20.00,
                'is_active' => true,
                'is_default' => true,
            ],
            [
                'code' => 'detergent_premium',
                'name' => 'น้ำยาซักผ้า สูตรพรีเมียม (ลดกลิ่นอับ/ถนอมสีผ้า)',
                'category' => 'detergent',
                'unit_price' => 30.00,
                'is_active' => true,
                'is_default' => false,
            ],
            [
                'code' => 'softener_standard',
                'name' => 'ปรับผ้านุ่ม กลิ่นมาตรฐาน (หอมละมุน)',
                'category' => 'softener',
                'unit_price' => 20.00,
                'is_active' => true,
                'is_default' => true,
            ],
            [
                'code' => 'softener_premium',
                'name' => 'ปรับผ้านุ่ม กลิ่นพรีเมียม (หอมติดทนนานพิเศษ)',
                'category' => 'softener',
                'unit_price' => 30.00,
                'is_active' => true,
                'is_default' => false,
            ]
        ];

        foreach ($addons as $addon) {
            AddonOption::updateOrCreate(['code' => $addon['code']], $addon);
        }
    }
}