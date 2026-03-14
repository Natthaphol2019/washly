<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    
    public function run(): void
    {
        $this->call([
            TimeSlotSeeder::class,     // ดึงข้อมูลรอบเวลา
            PackageSeeder::class,      // ดึงข้อมูลแพ็กเกจ
            AddonOptionSeeder::class,  // ดึงข้อมูลเมนูเสริม/น้ำยาซักผ้า
        ]);
        // 👑 สร้างบัญชี แอดมิน (Admin)
        User::create([
            'fullname' => 'ผู้ดูแลระบบ',
            'username' => 'admin',
            'password' => Hash::make('12345678'), // รหัสผ่านเทสต์
            'role' => 'admin',
            'phone' => '0800000000',
        ]);

        // 🛵 สร้างบัญชี พนักงานขับรถ (Driver)
        User::create([
            'fullname' => 'สมหมาย สายซิ่ง',
            'username' => 'driver',
            'password' => Hash::make('12345678'),
            'role' => 'driver',
            'phone' => '0811111111',
        ]);

        // 👤 สร้างบัญชี ลูกค้า (Customer)
        User::create([
            'fullname' => 'ลูกค้า ทดสอบ',
            'username' => 'customer',
            'password' => Hash::make('12345678'),
            'role' => 'customer',
            'phone' => '0822222222',
            'address' => 'บ้านเลขที่ 123 หมู่บ้านทดสอบ ตำบลคลองพระอุดม อำเภอลาดหลุมแก้ว ปทุมธานี',
        ]);
    }
}