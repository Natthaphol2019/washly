<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    // หน้ากระดานงาน
    public function index() {
        // งานที่ยังไม่มีใครรับ
        $availableOrders = Order::where('status', 'pending_pickup')
            ->whereNull('driver_id')
            ->orderBy('created_at', 'asc')->get();

        // งานของฉัน
        $myOrders = Order::where('driver_id', Auth::id())
            ->whereIn('status', ['picking_up', 'washing_completed', 'delivering'])
            ->orderBy('created_at', 'asc')->get();

        return view('driver.dashboard', compact('availableOrders', 'myOrders'));
    }

    // ไรเดอร์กดรับงาน
    public function acceptJob($id) {
        $order = Order::findOrFail($id);
        
        if ($order->driver_id !== null) {
            return back()->with('error', 'อ๊ะ! งานนี้มีเพื่อนรับไปแล้วค้าบ');
        }

        $order->driver_id = Auth::id();
        $order->status = 'picking_up'; // เด้งเป็นกำลังไปรับเลย
        $order->save();

        return back()->with('success', 'รับงานสำเร็จ! ลุยเลยค้าบ 🏍️');
    }

    // อัปเดตสถานะงานของตัวเอง
    public function updateDeliveryStatus(Request $request, $id) {
        $order = Order::where('id', $id)->where('driver_id', Auth::id())->firstOrFail();
        $order->status = $request->status;
        $order->save();
        return back()->with('success', 'อัปเดตสถานะเรียบร้อย!');
    }

    // หน้าประวัติงาน
    public function history() {
        $orders = Order::where('driver_id', Auth::id())
            ->where('status', 'completed')
            ->orderBy('updated_at', 'desc')->paginate(10);
        return view('driver.history', compact('orders'));
    }

    // หน้าโปรไฟล์
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