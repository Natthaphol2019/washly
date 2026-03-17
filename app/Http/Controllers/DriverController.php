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
use Illuminate\Support\Facades\Notification;

class DriverController extends Controller
{
    // ==========================================
    // 🛵 หน้ากระดานงาน (Dashboard)
    // ==========================================
    public function index() {
        $myOrders = Order::with(['user', 'package'])
            ->where('driver_id', Auth::id())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($order) {
                if (!$order->pickup_map_link || strpos($order->pickup_map_link, '?q=,') !== false) {
                    $order->pickup_map_link = $order->map_link;
                }
                return $order;
            });

        return view('driver.dashboard', compact('myOrders'));
    }

    // ==========================================
    // ✅ ไรเดอร์รับงาน (Accept Job)
    // ==========================================
    public function acceptJob($id)
    {
        $order = Order::findOrFail($id);

        if ($order->driver_id !== Auth::id()) {
            return back()->with('error', 'คุณไม่สามารถรับออเดอร์นี้ได้ครับ');
        }

        if (!in_array($order->status, ['pending', 'pending_pickup'])) {
            return back()->with('error', 'ออเดอร์นี้อยู่ในสถานะที่ไม่สามารถรับงานได้');
        }

        $oldStatus = $order->status;
        $order->status = 'picking_up';
        $order->save();

        OrderLog::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'old_status' => $oldStatus,
            'new_status' => 'picking_up'
        ]);

        // แจ้งเตือนลูกค้า
        if ($order->user) {
            $order->user->notify(new SystemNotification(
                'ไรเดอร์รับงานแล้ว 🛵',
                'ออเดอร์ ' . $order->order_number . ' ไรเดอร์กำลังเดินทางไปรับผ้าของคุณครับ',
                route('customer.orders'),
                'info'
            ));
        }

        // แจ้งเตือนแอดมิน (เพิ่มใหม่!)
        $admins = User::whereIn('role', ['admin', 'staff'])->get();
        Notification::send($admins, new SystemNotification(
            'ไรเดอร์เริ่มงานแล้ว',
            'ออเดอร์ ' . $order->order_number . ' ไรเดอร์ (' . Auth::user()->fullname . ') กำลังเดินทางไปรับผ้า',
            route('admin.orders.index'),
            'info'
        ));

        return back()->with('success', 'รับงานสำเร็จ! กำลังเดินทางไปรับผ้าครับ');
    }

    // ==========================================
    // 🚚 อัปเดตสถานะโดยไรเดอร์ (Full Control)
    // ==========================================
    public function updateStatus(Request $request, $id)
    {
        // 🚨 ปรับแก้: เพิ่ม picked_up เข้าไปให้ไรเดอร์กดได้
        $request->validate([
            'status' => 'required|in:pending,pending_pickup,picking_up,picked_up,processing,washing_completed,delivering,completed'
        ]);

        $order = Order::findOrFail($id);

        if ($order->status === 'cancelled' || $order->driver_id !== Auth::id()) {
            return back()->with('error', 'ไม่สามารถอัปเดตออเดอร์นี้ได้ครับ');
        }

        $oldStatus = $order->status;
        $newStatus = $request->status;

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
            'picked_up' => 'รับผ้ามาแล้ว', // 🚨 ปรับแก้: เติมคำแปล
            'processing' => 'กำลังซัก/อบ',
            'washing_completed' => 'ซักเสร็จ/รอส่ง',
            'delivering' => 'กำลังไปส่ง',
            'completed' => 'เสร็จสิ้น',
        ];

        // แจ้งเตือนลูกค้า
        if ($order->user) {
            $order->user->notify(new SystemNotification(
                'อัปเดตสถานะออเดอร์ 🛵',
                'ออเดอร์ ' . $order->order_number . ' เปลี่ยนเป็น "' . ($statusLabels[$newStatus] ?? $newStatus) . '"',
                route('customer.orders'),
                'info'
            ));
        }

        // แจ้งเตือนแอดมิน (เพิ่มใหม่!)
        $admins = User::whereIn('role', ['admin', 'staff'])->get();
        Notification::send($admins, new SystemNotification(
            'ไรเดอร์อัปเดตสถานะ',
            'ออเดอร์ ' . $order->order_number . ' เปลี่ยนเป็น "' . ($statusLabels[$newStatus] ?? $newStatus) . '"',
            route('admin.orders.index'),
            'info'
        ));

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

        // แจ้งเตือนแอดมิน (เพิ่มใหม่!)
        $admins = User::whereIn('role', ['admin', 'staff'])->get();
        Notification::send($admins, new SystemNotification(
            'ไรเดอร์ยกเลิกงาน 🚨',
            'ออเดอร์ ' . $order->order_number . ' ถูกยกเลิกโดยไรเดอร์ สาเหตุ: ' . $request->cancel_reason,
            route('admin.orders.index'),
            'danger'
        ));

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

        // แจ้งเตือนแอดมิน (เพิ่มใหม่!)
        $admins = User::whereIn('role', ['admin', 'staff'])->get();
        Notification::send($admins, new SystemNotification(
            'สลิปถูกอนุมัติโดยไรเดอร์',
            'ออเดอร์ ' . $order->order_number . ' อนุมัติสลิปเรียบร้อยแล้ว',
            route('admin.orders.index'),
            'success'
        ));

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

        // แจ้งเตือนแอดมิน (เพิ่มใหม่!)
        $admins = User::whereIn('role', ['admin', 'staff'])->get();
        Notification::send($admins, new SystemNotification(
            'ไรเดอร์เก็บเงินสดแล้ว',
            'ออเดอร์ ' . $order->order_number . ' ไรเดอร์เก็บเงินสดจากลูกค้าเรียบร้อยแล้ว 💰',
            route('admin.orders.index'),
            'success'
        ));

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

            // แจ้งเตือนแอดมิน (เพิ่มใหม่!)
            $admins = User::whereIn('role', ['admin', 'staff'])->get();
            Notification::send($admins, new SystemNotification(
                'ไรเดอร์ปฏิเสธสลิป',
                'ออเดอร์ ' . $order->order_number . ' ไรเดอร์ปฏิเสธสลิป ให้ลูกค้าอัปโหลดใหม่',
                route('admin.orders.index'),
                'warning'
            ));
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
