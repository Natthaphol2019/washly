<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    public function index()
    {
        // ดึงข้อมูลออเดอร์ของลูกค้าคนที่ล็อกอินอยู่ พร้อมดึงข้อมูลแพ็กเกจที่ผูกกันไว้มาด้วย (with)
        // จัดเรียงจากออเดอร์ล่าสุด (desc)
        $orders = Order::with(['package', 'logs'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.orders', compact('orders'));
    }


    // 1. เปิดหน้าแสดง QR Code และฟอร์มอัปโหลดสลิป
    public function paymentForm($id)
    {
        $order = Order::findOrFail($id);

        // เช็กความปลอดภัย: ต้องเป็นออเดอร์ของตัวเอง, ต้องเป็นโหมดโอน และต้องยังไม่ส่งหลักฐาน
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('customer.orders')->with('error', 'ไม่สามารถเข้าถึงออเดอร์นี้ได้');
        }

        if ($order->payment_method !== 'transfer') {
            return redirect()->route('customer.orders')->with('error', 'ออเดอร์นี้เลือกชำระเงินสด ไม่ต้องแนบสลิป');
        }

        if ($order->payment_status !== 'unpaid' || $order->payment_slip !== null) {
            return redirect()->route('customer.orders')->with('error', 'ออเดอร์นี้ชำระเงินไปแล้ว หรือมีสลิปอยู่ในระบบแล้ว');
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

        if ($order->user_id !== Auth::id()) {
            return redirect()->route('customer.orders')->with('error', 'ไม่สามารถอัปโหลดให้กับออเดอร์นี้ได้');
        }

        if ($order->payment_method !== 'transfer') {
            return redirect()->route('customer.orders')->with('error', 'ออเดอร์นี้เป็นเงินสดปลายทาง ไม่ต้องแนบสลิป');
        }

        if ($order->payment_status !== 'unpaid') {
            return redirect()->route('customer.orders')->with('error', 'ออเดอร์นี้ไม่ได้อยู่ในสถานะรอชำระ');
        }

        if ($request->hasFile('slip')) {
            $path = $request->file('slip')->store('slips', 'public');
            $order->payment_slip = $path;
            
            // 🚨 เพิ่มบรรทัดนี้! เปลี่ยนสถานะการจ่ายเงินเป็น "รอตรวจสอบ"
            $order->payment_status = 'reviewing'; 
            
            $order->save();

            $customer = Auth::user();
            $customer->notify(new SystemNotification(
                'ส่งสลิปแล้ว',
                'ระบบรับสลิปของออเดอร์ ' . $order->order_number . ' แล้ว กำลังรอแอดมินตรวจสอบ',
                route('customer.orders'),
                'info'
            ));

            $admins = User::whereIn('role', ['admin', 'staff'])->get();
            Notification::send($admins, new SystemNotification(
                'มีสลิปใหม่รอตรวจสอบ',
                'ออเดอร์ ' . $order->order_number . ' อัปโหลดสลิปเข้ามาแล้ว',
                route('admin.orders.index'),
                'warning'
            ));
        }

        return redirect()->route('customer.orders')->with('success_payment', 'อัปโหลดสลิปสำเร็จ! รอแอดมินตรวจสอบยอดเงินสักครู่นะครับ 💸');
    }
}