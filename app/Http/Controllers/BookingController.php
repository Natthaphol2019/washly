<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\TimeSlot;
use App\Models\Order; // 👈 อย่าลืมเรียกใช้ Model Order ด้วยนะครับ
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    // (ฟังก์ชัน showBookingForm เดิมของซี ปล่อยไว้เหมือนเดิมครับ)
    public function showBookingForm()
    {
        $packages = Package::all();
        $timeSlots = TimeSlot::all();
        return view('customer.book', compact('packages', 'timeSlots'));
    }

    // 🚀 เพิ่มฟังก์ชัน store รับข้อมูลและบันทึกลง Database
    public function store(Request $request)
    {
        // 1. ตรวจสอบข้อมูลก่อนว่าส่งมาครบไหม
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'time_slot_id' => 'required|exists:time_slots,id',
            'wash_temp' => 'required|in:เย็น,อุ่น,ร้อน',
            'dry_temp' => 'required|in:อุ่น,ปานกลาง,ร้อน',
            'pickup_address' => 'required|string',
        ]);

        $user = Auth::user();
        $package = Package::findOrFail($request->package_id);

        // 2. สร้างเลขที่ออเดอร์สุดเท่ (เช่น ORD-20260303-4512)
        $orderNumber = 'ORD-' . date('Ymd') . '-' . rand(1000, 9999);

        // 3. เริ่มสร้างออเดอร์ใหม่
        $order = new Order();
        $order->order_number = $orderNumber;
        $order->user_id = $user->id;
        $order->package_id = $request->package_id;
        $order->time_slot_id = $request->time_slot_id;
        $order->wash_temp = $request->wash_temp;
        $order->dry_temp = $request->dry_temp;
        
        // ดึงที่อยู่จากฟอร์ม (เพราะลูกค้าอาจจะพิมพ์ "ฝากป้อมยาม" เพิ่มมา)
        $order->pickup_address = $request->pickup_address;
        
        // 📍 พระเอกของเรา! ดึงพิกัด GPS จากหน้าโปรไฟล์มาใส่เงียบๆ แบบที่คุยกันไว้
        $order->pickup_latitude = $user->latitude;
        $order->pickup_longitude = $user->longitude;
        $order->pickup_map_link = $user->map_link;

        // ใส่ราคาสุทธิ และสถานะเริ่มต้น
        $order->total_price = $package->price;
        $order->status = 'pending'; // สถานะรอมารับผ้า
        $order->payment_status = 'unpaid'; // ยังไม่ได้จ่ายเงิน

        // บันทึกตู้ม! ลงตาราง orders
        $order->save();

        // 4. เด้งกลับไปหน้าแรก (Dashboard) พร้อมส่งข้อความแจ้งเตือนไปบอก
        return redirect()->route('customer.main')->with('success_order', 'จองคิวสำเร็จ! หมายเลขออเดอร์ของคุณคือ ' . $orderNumber);
    }
}