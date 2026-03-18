<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderLog;
use App\Models\Package;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🚀 กำลังสร้างข้อมูล Demo สำหรับพรีเซนต์...');

        // 1. สร้างลูกค้าจำลอง 3 คน
        $customers = [
            User::firstOrCreate(
                ['username' => 'demo_somying'],
                ['fullname' => 'คุณสมหญิง รักสะอาด', 'password' => Hash::make('password'), 'role' => 'customer', 'phone' => '0812345678', 'address' => 'คอนโด A ชั้น 5', 'latitude' => 14.0352, 'longitude' => 100.7351]
            ),
            User::firstOrCreate(
                ['username' => 'demo_jaidee'],
                ['fullname' => 'คุณใจดี มีเวลา', 'password' => Hash::make('password'), 'role' => 'customer', 'phone' => '0898765432', 'address' => 'หมู่บ้าน B ซอย 5', 'latitude' => 14.0426, 'longitude' => 100.7343]
            ),
            User::firstOrCreate(
                ['username' => 'demo_somsak'],
                ['fullname' => 'คุณสมศักดิ์ สายชิล', 'password' => Hash::make('password'), 'role' => 'customer', 'phone' => '0861112222', 'address' => 'หอพัก C ใกล้มหาลัย', 'latitude' => 14.0388, 'longitude' => 100.7299]
            )
        ];

        // 2. ดึงข้อมูลพนักงานและคนขับ (ถ้าไม่มีให้สร้างใหม่)
        $driver = User::where('role', 'driver')->first() ?? User::firstOrCreate(
            ['username' => 'demo_driver'], 
            ['fullname' => 'พี่ไรเดอร์ สายซิ่ง', 'password' => Hash::make('password'), 'role' => 'driver', 'phone' => '0800000000']
        );

        // ดึงแพ็กเกจและรอบเวลาที่มีในระบบมาใช้
        $packages = Package::all();
        $timeSlots = TimeSlot::all();

        if($packages->isEmpty() || $timeSlots->isEmpty()) {
            $this->command->error('❌ ไม่พบข้อมูลแพ็กเกจ หรือ รอบเวลา กรุณาเพิ่มในระบบก่อนรัน Demo Data!');
            return;
        }

        // ตัวอย่างเมนูเสริมจำลอง (JSON) เพื่อให้หน้า Dashboard โชว์สถิติได้
        $dummyAddons = [
            [
                'code' => 'detergent_softener',
                'name' => 'น้ำยาซัก + ปรับผ้านุ่ม',
                'category' => 'detergent',
                'unit_price' => 10,
                'qty' => 1,
                'line_total' => 10
            ],
            [
                'code' => 'extra_dry',
                'name' => 'อบผ้าเพิ่ม (10 นาที)',
                'category' => 'service',
                'unit_price' => 10,
                'qty' => 1,
                'line_total' => 10
            ]
        ];

        // 3. จำลองสร้างออเดอร์หลากหลายสถานะ
        $ordersData = [
            // ออเดอร์ที่ 1: เสร็จสิ้นแล้ว ได้รายรับเป็น "เงินสด" (สร้างเมื่อ 2 วันก่อน)
            [
                'user_id' => $customers[0]->id,
                'driver_id' => $driver->id,
                'package_id' => $packages->first()->id,
                'time_slot_id' => $timeSlots->first()->id,
                'status' => 'completed',
                'payment_method' => 'cash',
                'payment_status' => 'paid',
                'subtotal' => $packages->first()->price,
                'addon_total' => 20,
                'delivery_fee' => 0,
                'total_price' => $packages->first()->price + 20,
                'created_at' => Carbon::now()->subDays(2)->setHour(10)->setMinute(30),
                'pickup_date' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'pickup_address' => $customers[0]->address,
            ],
            // ออเดอร์ที่ 2: เสร็จสิ้นแล้ว ได้รายรับเป็น "โอนเงิน" (สร้างเมื่อวาน)
            [
                'user_id' => $customers[1]->id,
                'driver_id' => $driver->id,
                'package_id' => $packages->last()->id,
                'time_slot_id' => $timeSlots->last()->id,
                'status' => 'completed',
                'payment_method' => 'transfer',
                'payment_status' => 'paid',
                'subtotal' => $packages->last()->price,
                'addon_total' => 20,
                'delivery_fee' => 20,
                'total_price' => $packages->last()->price + 40,
                'created_at' => Carbon::now()->subDays(1)->setHour(14)->setMinute(15),
                'pickup_date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                'pickup_address' => $customers[1]->address,
            ],
            // ออเดอร์ที่ 3: กำลังซัก/อบ (จ่ายเงินผ่าน QR แล้ว)
            [
                'user_id' => $customers[0]->id,
                'driver_id' => $driver->id,
                'package_id' => $packages->first()->id,
                'time_slot_id' => $timeSlots->first()->id,
                'status' => 'processing',
                'payment_method' => 'transfer',
                'payment_status' => 'paid',
                'subtotal' => $packages->first()->price,
                'addon_total' => 20,
                'delivery_fee' => 0,
                'total_price' => $packages->first()->price + 20,
                'created_at' => Carbon::now()->setHour(8)->setMinute(10),
                'pickup_date' => Carbon::now()->format('Y-m-d'),
                'pickup_address' => $customers[0]->address,
            ],
            // ออเดอร์ที่ 4: กำลังไปรับผ้า (รอเก็บเงินสดปลายทาง)
            [
                'user_id' => $customers[2]->id,
                'driver_id' => $driver->id,
                'package_id' => $packages->last()->id,
                'time_slot_id' => $timeSlots->first()->id,
                'status' => 'picking_up',
                'payment_method' => 'cash',
                'payment_status' => 'pending_cash',
                'subtotal' => $packages->last()->price,
                'addon_total' => 20,
                'delivery_fee' => 20,
                'total_price' => $packages->last()->price + 40,
                'created_at' => Carbon::now()->subMinutes(30),
                'pickup_date' => Carbon::now()->format('Y-m-d'),
                'pickup_address' => $customers[2]->address,
            ],
            // ออเดอร์ที่ 5: เพิ่งจองเข้ามาใหม่สดๆ ร้อนๆ (โอนเงินแล้ว รอแอดมินตรวจสลิป)
            [
                'user_id' => $customers[1]->id,
                'driver_id' => null, // ยังไม่มีคนขับ
                'package_id' => $packages->first()->id,
                'time_slot_id' => $timeSlots->last()->id,
                'status' => 'pending',
                'payment_method' => 'transfer',
                'payment_status' => 'reviewing',
                'payment_slip' => 'demo_slip.jpg', // จำลองว่ามีสลิป
                'subtotal' => $packages->first()->price,
                'addon_total' => 20,
                'delivery_fee' => 20,
                'total_price' => $packages->first()->price + 40,
                'created_at' => Carbon::now()->subMinutes(5),
                'pickup_date' => Carbon::now()->addDay()->format('Y-m-d'), // จองคิวของพรุ่งนี้
                'pickup_address' => $customers[1]->address,
            ]
        ];

        foreach ($ordersData as $data) {
            $order = new Order();
            $order->order_number = 'ORD-DEMO-' . rand(1000, 9999);
            $order->fill($data);
            $order->selected_addons = $dummyAddons; // ใส่ข้อมูลเมนูเสริมให้ทุกออเดอร์
            $order->save();

            // 4. จำลองประวัติ Timeline ของออเดอร์ (Order Log)
            OrderLog::create([
                'order_id' => $order->id,
                'user_id' => $data['user_id'],
                'old_status' => 'pending',
                'new_status' => $data['status'],
                'note' => 'จำลองข้อมูล Demo (' . $data['status'] . ')',
                'created_at' => $data['created_at'],
                'updated_at' => $data['created_at'],
            ]);
        }

        $this->command->info('✅ สร้างข้อมูล Demo สำเร็จ! เข้าไปดูหน้า Dashboard แอดมินได้เลยครับ!');
    }
}