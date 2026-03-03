<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        // ดึงข้อมูลออเดอร์ของลูกค้าคนที่ล็อกอินอยู่ พร้อมดึงข้อมูลแพ็กเกจที่ผูกกันไว้มาด้วย (with)
        // จัดเรียงจากออเดอร์ล่าสุด (desc)
        $orders = Order::with('package')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.orders', compact('orders'));
    }


    // 1. เปิดหน้าแสดง QR Code และฟอร์มอัปโหลดสลิป
    public function paymentForm($id)
    {
        $order = Order::findOrFail($id);

        // เช็กความปลอดภัย: ต้องเป็นออเดอร์ของตัวเอง และยังไม่ได้จ่ายเงิน
        if ($order->user_id !== Auth::id() || $order->payment_slip !== null) {
            return redirect()->route('customer.orders')->with('error', 'ออเดอร์นี้ชำระเงินไปแล้ว หรือไม่ใช่ออเดอร์ของคุณ');
        }

        return view('customer.pay', compact('order'));
    }

    // 2. รับไฟล์สลิป บันทึกลงโฟลเดอร์ และอัปเดตฐานข้อมูล
    public function uploadSlip(Request $request, $id)
    {
        $request->validate([
            'slip' => 'required|image|mimes:jpeg,png,jpg|max:5120', 
        ]);

        $order = Order::findOrFail($id);

        if ($request->hasFile('slip')) {
            $path = $request->file('slip')->store('slips', 'public');
            $order->payment_slip = $path;
            
            // 🚨 เพิ่มบรรทัดนี้! เปลี่ยนสถานะการจ่ายเงินเป็น "รอตรวจสอบ"
            $order->payment_status = 'reviewing'; 
            
            $order->save();
        }

        return redirect()->route('customer.orders')->with('success_payment', 'อัปโหลดสลิปสำเร็จ! รอแอดมินตรวจสอบยอดเงินสักครู่นะครับ 💸');
    }
}