<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Package;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OrderFullFlowTest extends TestCase
{
    use RefreshDatabase; // รีเซ็ตฐานข้อมูลใหม่ทุกครั้งที่เทส

    public function test_full_order_lifecycle_end_to_end()
    {
        // 🌟 [เตรียมข้อมูล] สร้างผู้ใช้ 3 บทบาท และข้อมูลพื้นฐาน
        Storage::fake('public'); // จำลองโฟลเดอร์สำหรับเก็บสลิป

        // 1. สร้างลูกค้า
        $customer = User::factory()->create(['role' => 'customer', 'delivery_distance' => 2.0]);

        // 2. สร้างแอดมิน
        $admin = User::factory()->create(['role' => 'admin']);

        // 3. สร้างคนขับ (ไรเดอร์)
        $driver = User::factory()->create(['role' => 'driver']);

        // 4. สร้างแพ็กเกจและรอบเวลา
        $package = Package::create(['name' => 'ซัก-อบ 9-10 kg', 'price' => 120, 'is_active' => true]);
        $timeSlot = TimeSlot::create(['round_name' => '09:00 - 12:00']);

        // ========================================================
        // 🎬 สเต็ปที่ 1: ลูกค้าทำการจองคิว
        // ========================================================
        $this->actingAs($customer)->post(route('customer.book.store'), [
            'pickup_date' => 'tomorrow',
            'package_id' => $package->id,
            'time_slot_id' => $timeSlot->id,
            'pickup_address' => 'คอนโด Washly ชั้น 5',
            'payment_method' => 'transfer', // เลือกโอนเงิน
            'use_customer_detergent' => true,
            'use_customer_softener' => true,
        ])->assertSessionHas('success_order');

        // ดึงออเดอร์ที่เพิ่งสร้างขึ้นมาตรวจสอบ
        $order = Order::first();
        $this->assertNotNull($order);
        $this->assertEquals('pending', $order->status);
        $this->assertEquals('unpaid', $order->payment_status);

        // ========================================================
        // 🎬 สเต็ปที่ 2: แอดมินจ่ายงานให้คนขับ
        // ========================================================
        $this->actingAs($admin)->post(route('admin.orders.assign_driver', $order->id), [
            'driver_id' => $driver->id
        ]);

        // เช็กว่าออเดอร์มีชื่อคนขับติดไปแล้ว
        $order->refresh();
        $this->assertEquals($driver->id, $order->driver_id);

        // ========================================================
        // 🎬 สเต็ปที่ 3: ลูกค้าแนบสลิปโอนเงิน
        // ========================================================
        // สร้างไฟล์รูปภาพจำลอง
        $fakeSlip = UploadedFile::fake()->image('slip.jpg');

        $this->actingAs($customer)->post(url('/customer/orders/' . $order->id . '/upload-slip'), [
            'slip' => $fakeSlip
        ]);

        // เช็กว่ารูปถูกอัปโหลด และสถานะเปลี่ยนเป็น "รอตรวจสอบ"
        $order->refresh();
        $this->assertNotNull($order->payment_slip);
        $this->assertEquals('reviewing', $order->payment_status);

        // ========================================================
        // 🎬 สเต็ปที่ 4: แอดมินตรวจสอบและอนุมัติสลิป
        // ========================================================
        $this->actingAs($admin)->post(route('admin.orders.approve_payment', $order->id));

        // เช็กว่าสถานะการจ่ายเงินเปลี่ยนเป็น "จ่ายแล้ว"
        $order->refresh();
        $this->assertEquals('paid', $order->payment_status);

        // ========================================================
        // 🎬 สเต็ปที่ 5: แอดมิน/คนขับ อัปเดตสถานะจนจบงาน
        // ========================================================
        // สถานะ: กำลังไปรับ -> ซัก/อบ -> กำลังไปส่ง -> เสร็จสิ้น
        $statuses = ['picking_up', 'processing', 'delivering', 'completed'];

        foreach ($statuses as $status) {
            $this->actingAs($admin)->put(route('admin.orders.status', $order->id), [
                'status' => $status
            ]);

            $order->refresh();
            $this->assertEquals($status, $order->status);
        }

        // เช็กขั้นสุดท้าย! ออเดอร์ต้องสถานะเป็น completed และ paid
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'completed',
            'payment_status' => 'paid',
        ]);
    }
}