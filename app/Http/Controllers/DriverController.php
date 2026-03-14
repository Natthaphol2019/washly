<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderLog;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller
{
    // ==========================================
    // 🛵 หน้ากระดานงาน (Dashboard)
    // ==========================================
    public function index() {
        // งานของฉัน (โชว์ทุกสถานะที่ยังไม่เสร็จหรือยกเลิก)
        $myOrders = Order::with(['user', 'package'])
            ->where('driver_id', Auth::id())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->orderBy('created_at', 'asc')->get();

        return view('driver.dashboard', compact('myOrders'));
    }

    // ==========================================
    // 🚚 อัปเดตสถานะโดยไรเดอร์ (Full Control)
    // ==========================================
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,pending_pickup,picking_up,processing,washing_completed,delivering,completed'
        ]);

        $order = Order::findOrFail($id);

        if ($order->status === 'cancelled' || $order->driver_id !== Auth::id()) {
            return back()->with('error', 'ไม่สามารถอัปเดตออเดอร์นี้ได้ครับ');
        }

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // ถ้าเลือกสถานะเดิมซ้ำ ให้เด้งกลับเลย ไม่ต้องบันทึกให้รก DB
        if ($oldStatus === $newStatus) {
            return back();
        }

        $order->status = $newStatus;
        $order->save();

        OrderLog::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'old_status' => $oldStatus,
            'new_status' => $newStatus
        ]);

        $statusLabels = [
            'pending_pickup' => 'รอรับผ้า',
            'picking_up' => 'กำลังไปรับ',
            'processing' => 'กำลังซัก/อบ',
            'washing_completed' => 'ซักเสร็จ/รอส่ง',
            'delivering' => 'กำลังไปส่ง',
            'completed' => 'เสร็จสิ้น',
        ];

        if ($order->user) {
            $order->user->notify(new SystemNotification(
                'อัปเดตสถานะออเดอร์ 🛵',
                'ออเดอร์ ' . $order->order_number . ' เปลี่ยนเป็น "' . ($statusLabels[$newStatus] ?? $newStatus) . '"',
                route('customer.orders'),
                'info'
            ));
        }

        return back()->with('success', 'อัปเดตสถานะเป็น "' . ($statusLabels[$newStatus] ?? $newStatus) . '" สำเร็จ!');
    }

    // ==========================================
    // ❌ ไรเดอร์ยกเลิกงาน (พร้อมระบุเหตุผล)
    // ==========================================
    public function cancelOrder(Request $request, $id)
    {
        $request->validate(['cancel_reason' => 'required|string|max:1000']);
        $order = Order::findOrFail($id);

        if ($order->status === 'cancelled' || $order->driver_id !== Auth::id()) {
            return back()->with('error', 'ไม่สามารถยกเลิกออเดอร์นี้ได้ครับ');
        }

        $oldStatus = $order->status;
        $order->status = 'cancelled';
        
        $driverName = Auth::user()->fullname;
        $order->cancel_reason = "[ยกเลิกโดยไรเดอร์ $driverName] สาเหตุ: " . $request->cancel_reason;
        $order->save();

        OrderLog::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'old_status' => $oldStatus,
            'new_status' => 'cancelled'
        ]);

        if ($order->user) {
            $order->user->notify(new SystemNotification(
                'ไรเดอร์ขอยกเลิกงานรับส่งผ้า ❌',
                'ออเดอร์ของคุณถูกยกเลิก เนื่องจาก: ' . $request->cancel_reason,
                route('customer.orders'),
                'danger'
            ));
        }

        return back()->with('success', 'ยกเลิกออเดอร์เรียบร้อยแล้ว');
    }

    // ==========================================
    // 💰 การจัดการชำระเงินโดยคนขับ (รับเงิน/ตรวจสลิป)
    // ==========================================
    public function approvePayment($id)
    {
        $order = Order::where('driver_id', Auth::id())->findOrFail($id);
        $order->payment_status = 'paid';
        $order->save();

        if ($order->user) {
            $order->user->notify(new SystemNotification('ยืนยันการชำระเงินแล้ว', 'ออเดอร์ ' . $order->order_number . ' ตรวจสอบสลิปเรียบร้อยโดยไรเดอร์', route('customer.orders'), 'success'));
        }
        return back()->with('success', 'อนุมัติสลิปสำเร็จ! ✅');
    }

    public function confirmCashPayment($id)
    {
        $order = Order::where('driver_id', Auth::id())->findOrFail($id);
        if ($order->payment_status !== 'pending_cash') {
            return back()->with('error', 'ออเดอร์ไม่ได้อยู่ในสถานะรอรับเงินสด');
        }

        $order->payment_status = 'paid';
        $order->save();

        if ($order->user) {
            $order->user->notify(new SystemNotification('เก็บเงินปลายทางสำเร็จ', 'ออเดอร์ ' . $order->order_number . ' ไรเดอร์รับเงินสดเรียบร้อยแล้ว', route('customer.orders'), 'success'));
        }
        return back()->with('success', 'ยืนยันรับเงินสดเรียบร้อย! ✅');
    }

    public function rejectSlip($id)
    {
        $order = Order::where('driver_id', Auth::id())->findOrFail($id);
        if ($order->payment_slip) {
            Storage::disk('public')->delete($order->payment_slip);
            $order->payment_slip = null;
            $order->payment_status = 'unpaid';
            $order->save();

            if ($order->user) {
                $order->user->notify(new SystemNotification('สลิปโอนเงินถูกปฏิเสธ', 'ออเดอร์ ' . $order->order_number . ' กรุณาตรวจสอบและอัปโหลดสลิปใหม่อีกครั้ง', route('customer.orders.pay', $order->id), 'warning'));
            }
        }
        return back()->with('success', 'ปฏิเสธสลิปแล้ว ลูกค้าต้องอัปโหลดใหม่ 🔄');
    }

    // ==========================================
    // 📋 ประวัติและโปรไฟล์
    // ==========================================
    public function history() {
        $orders = Order::where('driver_id', Auth::id())
            ->whereIn('status', ['completed', 'cancelled'])
            ->orderBy('updated_at', 'desc')->paginate(10);
            
        return view('driver.history', compact('orders'));
    }

    public function profile() {
        return view('driver.profile');
    }

    public function updateProfile(Request $request) {
        $user = User::find(Auth::id());
        $request->validate([
            'fullname' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'password' => 'nullable|min:8'
        ]);

        $user->fullname = $request->fullname;
        $user->phone = $request->phone;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return back()->with('success', 'อัปเดตโปรไฟล์เรียบร้อย!');
    }
}