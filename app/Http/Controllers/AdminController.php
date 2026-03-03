<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Package;
use App\Models\OrderLog; // 👈 เรียกใช้งาน OrderLog
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // 1. ดึงออเดอร์ทั้งหมดมาแสดงในหน้า Dashboard
    public function index()
    {
        // ดึงข้อมูลออเดอร์ พร้อมข้อมูลลูกค้า(user) และแพ็กเกจ(package)
        $orders = Order::with(['user', 'package'])
            ->orderBy('created_at', 'desc')
            ->get();

        $packages = Package::orderBy('price')->get();

        return view('admin.dashboard', compact('orders', 'packages'));
    }
    // 2. 🚨 เพิ่มฟังก์ชันใหม่: หน้าจัดการออเดอร์แบบเต็มๆ
    public function manageOrders()
    {
        // ดึงข้อมูลออเดอร์ทั้งหมด พร้อมข้อมูลลูกค้าและแพ็กเกจ (เรียงจากใหม่ไปเก่า)
        $orders = Order::with(['user', 'package'])->orderBy('created_at', 'desc')->get();
        return view('admin.orders', compact('orders'));
    }
    // 2. ฟังก์ชันสำหรับอัปเดตสถานะผ้า และบันทึกประวัติ (Timeline)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,picked_up,processing,delivering,completed,cancelled'
        ]);

        $order = Order::findOrFail($id);

        // เก็บสถานะเก่าไว้เปรียบเทียบ
        $oldStatus = $order->status;
        $newStatus = $request->status;

        // ถ้าสถานะเปลี่ยน ค่อยบันทึกประวัติลงตาราง order_logs
        if ($oldStatus !== $newStatus) {

            // อัปเดตสถานะใหม่ในออเดอร์
            $order->status = $newStatus;
            $order->save();

            // 🌟 บันทึก Timeline ลงตาราง order_logs
            OrderLog::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(), // ไอดีของแอดมินที่กดเปลี่ยน
                'old_status' => $oldStatus,
                'new_status' => $newStatus
            ]);
        }

        return redirect()->back()->with('success', 'อัปเดตสถานะออเดอร์ ' . $order->order_number . ' เป็นที่เรียบร้อย! ✅');
    }
    // 1. เพิ่มฟังก์ชันใหม่: อนุมัติสลิป
    public function approvePayment($id)
    {
        $order = Order::findOrFail($id);
        $order->payment_status = 'paid'; // เปลี่ยนสถานะเป็นชำระแล้ว!
        $order->save();

        return redirect()->back()->with('success', 'ยืนยันยอดเงินสำเร็จ! ออเดอร์เปลี่ยนเป็นสถานะชำระเงินแล้ว ✅');
    }

    // 2. อัปเดตฟังก์ชันเดิม: ปฏิเสธสลิป (เพิ่มการรีเซ็ต payment_status)
    public function rejectSlip($id)
    {
        $order = Order::findOrFail($id);

        if ($order->payment_slip) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($order->payment_slip);
            $order->payment_slip = null;

            // 🚨 เพิ่มบรรทัดนี้: รีเซ็ตสถานะกลับไปเป็น "ยังไม่ชำระเงิน"
            $order->payment_status = 'unpaid';

            $order->save();
        }

        return redirect()->back()->with('success', 'ลบสลิปที่ไม่ถูกต้องเรียบร้อยแล้ว ลูกค้าสามารถอัปโหลดใหม่ได้ครับ 🔄');
    }
    // 1. หน้าแสดงรายการแพ็กเกจทั้งหมด
    public function packages()
    {
        $packages = Package::orderBy('price', 'asc')->get();
        return view('admin.packages', compact('packages'));
    }

    // 2. บันทึกแพ็กเกจใหม่
    public function storePackage(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        Package::create($request->all());

        return redirect()->route('admin.packages.index')->with('success', 'เพิ่มแพ็กเกจใหม่เรียบร้อยแล้ว! 🎉');
    }

    // 3. อัปเดตข้อมูล/ราคาแพ็กเกจ
    public function updatePackage(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        $package = Package::findOrFail($id);
        $package->update($request->all());

        return redirect()->route('admin.packages.index')->with('success', 'อัปเดตข้อมูลแพ็กเกจเรียบร้อยแล้ว! ✅');
    }

    // 4. ลบแพ็กเกจ
    public function destroyPackage($id)
    {
        $package = Package::findOrFail($id);
        $package->delete();

        return redirect()->route('admin.packages.index')->with('success', 'ลบแพ็กเกจออกจากระบบแล้ว 🗑️');
    }
}