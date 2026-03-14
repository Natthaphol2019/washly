<?php

namespace Database\Seeders;

use App\Models\TimeSlot;
use Illuminate\Database\Seeder;

class TimeSlotSeeder extends Seeder
{
    public function run(): void
    {
        $slots = [
            ['round_name' => 'รอบเช้า (08:00 - 12:00)', 'max_quota' => 20],
            ['round_name' => 'รอบบ่าย (13:00 - 17:00)', 'max_quota' => 20],
        ];

        foreach ($slots as $slot) {
            TimeSlot::updateOrCreate(
                ['round_name' => $slot['round_name']], // เช็กว่าชื่อนี้มีหรือยัง จะได้ไม่ซ้ำ
                $slot
            );
        }
    }
}