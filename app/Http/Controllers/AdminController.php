<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Package;
use App\Models\AddonOption;
use App\Models\User;
use App\Notifications\SystemNotification;
use App\Models\OrderLog; // 👈 เรียกใช้งาน OrderLog
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    private function normalizeAddonCode(string $value): string
    {
        return trim(Str::slug($value, '_'), '_');
    }

    private function makeUniqueAddonCode(string $baseCode, string $category): string
    {
        $normalized = $this->normalizeAddonCode($baseCode);
        if ($normalized === '') {
            $normalized = $this->normalizeAddonCode($category . '_addon');
        }

        $candidate = $normalized;
        $suffix = 1;

        while (AddonOption::where('code', $candidate)->exists()) {
            $candidate = $normalized . '_' . $suffix;
            $suffix++;
        }

        return $candidate;
    }

    private function ensureAddonCatalog(): void
    {
        if (AddonOption::count() > 0) {
            return;
        }

        AddonOption::insert([
            [
                'code' => 'detergent_softener',
                'name' => 'น้ำยาซัก + ปรับผ้านุ่ม',
                'category' => 'detergent',
                'unit_price' => 10,
                'is_active' => true,
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'detergent_hypo',
                'name' => 'น้ำยาซักสูตรอ่อนโยน',
                'category' => 'detergent',
                'unit_price' => 12,
                'is_active' => true,
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'detergent_antibac',
                'name' => 'น้ำยาซักฆ่าเชื้อ',
                'category' => 'detergent',
                'unit_price' => 15,
                'is_active' => true,
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'softener_standard',
                'name' => 'น้ำยาปรับผ้านุ่มมาตรฐาน',
                'category' => 'softener',
                'unit_price' => 10,
                'is_active' => true,
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'softener_mild',
                'name' => 'น้ำยาปรับผ้านุ่มสูตรอ่อนโยน',
                'category' => 'softener',
                'unit_price' => 12,
                'is_active' => true,
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'fold_premium',
                'name' => 'พับผ้าพรีเมียมแยกประเภท',
                'category' => 'service',
                'unit_price' => 8,
                'is_active' => true,
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    // 1. ดึงออเดอร์ทั้งหมดมาแสดงในหน้า Dashboard
    public function index()
    {
        // ดึงข้อมูลออเดอร์ พร้อมข้อมูลลูกค้า(user) และแพ็กเกจ(package)
        $orders = Order::with(['user', 'package'])
            ->orderBy('created_at', 'desc')
            ->get();

        $packages = Package::orderBy('price')->get();
        $paidCashTotal = $orders->where('payment_status', 'paid')->where('payment_method', 'cash')->sum('total_price');
        $paidTransferTotal = $orders->where('payment_status', 'paid')->where('payment_method', 'transfer')->sum('total_price');

        $addonAgg = [];
        $paidOrders = $orders->where('payment_status', 'paid');

        foreach ($paidOrders as $order) {
            foreach ((array) $order->selected_addons as $addon) {
                $code = (string) ($addon['code'] ?? 'unknown');
                $name = (string) ($addon['name'] ?? 'ไม่ระบุเมนู');
                $qty = (int) ($addon['qty'] ?? 0);
                $lineTotal = (float) ($addon['line_total'] ?? 0);

                if (!isset($addonAgg[$code])) {
                    $addonAgg[$code] = [
                        'code' => $code,
                        'name' => $name,
                        'qty' => 0,
                        'revenue' => 0,
                    ];
                }

                $addonAgg[$code]['qty'] += $qty;
                $addonAgg[$code]['revenue'] += $lineTotal;
            }
        }

        $topAddonStats = collect($addonAgg)
            ->sortByDesc('qty')
            ->take(5)
            ->values();

        return view('admin.dashboard', compact('orders', 'packages', 'paidCashTotal', 'paidTransferTotal', 'topAddonStats'));
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

            $statusLabels = [
                'pending' => 'รอรับผ้า',
                'picked_up' => 'รับผ้าแล้ว',
                'processing' => 'กำลังซัก/อบ',
                'delivering' => 'กำลังนำส่ง',
                'completed' => 'เสร็จสิ้น',
                'cancelled' => 'ยกเลิก',
            ];

            if ($order->user) {
                $order->user->notify(new SystemNotification(
                    'อัปเดตสถานะออเดอร์',
                    'ออเดอร์ ' . $order->order_number . ' เปลี่ยนเป็น "' . ($statusLabels[$newStatus] ?? $newStatus) . '"',
                    route('customer.orders'),
                    'info'
                ));
            }

            $admins = User::whereIn('role', ['admin', 'staff'])
                ->where('id', '!=', Auth::id())
                ->get();

            Notification::send($admins, new SystemNotification(
                'สถานะออเดอร์ถูกอัปเดต',
                'ออเดอร์ ' . $order->order_number . ' ถูกอัปเดตเป็น "' . ($statusLabels[$newStatus] ?? $newStatus) . '"',
                route('admin.orders.index'),
                'info'
            ));
        }

        return redirect()->back()->with('success', 'อัปเดตสถานะออเดอร์ ' . $order->order_number . ' เป็นที่เรียบร้อย! ✅');
    }
    // 1. เพิ่มฟังก์ชันใหม่: อนุมัติสลิป
    public function approvePayment($id)
    {
        $order = Order::with('user')->findOrFail($id);
        $order->payment_status = 'paid'; // เปลี่ยนสถานะเป็นชำระแล้ว!
        $order->save();

        if ($order->user) {
            $order->user->notify(new SystemNotification(
                'ยืนยันการชำระเงินแล้ว',
                'ออเดอร์ ' . $order->order_number . ' ผ่านการตรวจสอบสลิปเรียบร้อยแล้ว',
                route('customer.orders'),
                'success'
            ));
        }

        $admins = User::whereIn('role', ['admin', 'staff'])
            ->where('id', '!=', Auth::id())
            ->get();

        Notification::send($admins, new SystemNotification(
            'อนุมัติสลิปสำเร็จ',
            'ออเดอร์ ' . $order->order_number . ' ถูกอนุมัติการชำระเงินแล้ว',
            route('admin.orders.index'),
            'success'
        ));

        return redirect()->back()->with('success', 'ยืนยันยอดเงินสำเร็จ! ออเดอร์เปลี่ยนเป็นสถานะชำระเงินแล้ว ✅');
    }

    public function confirmCashPayment($id)
    {
        $order = Order::with('user')->findOrFail($id);

        if ($order->payment_method !== 'cash') {
            return redirect()->back()->with('error', 'ออเดอร์นี้ไม่ได้เลือกชำระเงินสด');
        }

        if ($order->payment_status !== 'pending_cash') {
            return redirect()->back()->with('error', 'ออเดอร์นี้ไม่ได้อยู่ในสถานะรอรับเงินสด');
        }

        $order->payment_status = 'paid';
        $order->save();

        if ($order->user) {
            $order->user->notify(new SystemNotification(
                'ยืนยันการรับเงินสดแล้ว',
                'ออเดอร์ ' . $order->order_number . ' ถูกยืนยันว่าเก็บเงินสดเรียบร้อยแล้ว',
                route('customer.orders'),
                'success'
            ));
        }

        return redirect()->back()->with('success', 'ยืนยันรับเงินสดเรียบร้อยแล้ว ✅');
    }

    // 2. อัปเดตฟังก์ชันเดิม: ปฏิเสธสลิป (เพิ่มการรีเซ็ต payment_status)
    public function rejectSlip($id)
    {
        $order = Order::with('user')->findOrFail($id);

        if ($order->payment_slip) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($order->payment_slip);
            $order->payment_slip = null;

            // 🚨 เพิ่มบรรทัดนี้: รีเซ็ตสถานะกลับไปเป็น "ยังไม่ชำระเงิน"
            $order->payment_status = 'unpaid';

            $order->save();

            if ($order->user) {
                $order->user->notify(new SystemNotification(
                    'สลิปถูกปฏิเสธ',
                    'ออเดอร์ ' . $order->order_number . ' ต้องอัปโหลดสลิปใหม่อีกครั้ง',
                    route('customer.orders.pay', $order->id),
                    'warning'
                ));
            }

            $admins = User::whereIn('role', ['admin', 'staff'])
                ->where('id', '!=', Auth::id())
                ->get();

            Notification::send($admins, new SystemNotification(
                'ปฏิเสธสลิปเรียบร้อย',
                'ออเดอร์ ' . $order->order_number . ' ถูกปฏิเสธสลิปและรออัปโหลดใหม่',
                route('admin.orders.index'),
                'warning'
            ));
        }

        return redirect()->back()->with('success', 'ลบสลิปที่ไม่ถูกต้องเรียบร้อยแล้ว ลูกค้าสามารถอัปโหลดใหม่ได้ครับ 🔄');
    }
    // 1. หน้าแสดงรายการแพ็กเกจทั้งหมด
    public function packages()
    {
        $this->ensureAddonCatalog();

        $packages = Package::orderBy('price', 'asc')->get();
        $addonOptions = AddonOption::orderBy('category')->orderBy('name')->get();
        $detergentOptions = $addonOptions->where('category', 'detergent')->values();

        return view('admin.packages', compact('packages', 'addonOptions', 'detergentOptions'));
    }

    // 2. บันทึกแพ็กเกจใหม่
    public function storePackage(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'default_detergent_code' => 'nullable|string|exists:addon_options,code',
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
            'description' => 'nullable|string',
            'default_detergent_code' => 'nullable|string|exists:addon_options,code',
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

    public function storeAddon(Request $request)
    {
        $request->validate([
            'code' => 'nullable|string|max:80',
            'name' => 'required|string|max:255',
            'category' => 'required|in:detergent,softener,service',
            'unit_price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'nullable|boolean',
            'is_default' => 'nullable|boolean',
        ]);

        $manualCode = trim((string) $request->input('code', ''));
        $autoBase = $request->input('category') . '_' . $request->input('name');
        $code = $this->makeUniqueAddonCode($manualCode !== '' ? $manualCode : $autoBase, (string) $request->input('category'));

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('addons', 'public');
        }

        if ($request->boolean('is_default')) {
            $category = $request->input('category');
            if (in_array($category, ['detergent', 'softener'], true)) {
                AddonOption::where('category', $category)->update(['is_default' => false]);
            }
        }

        AddonOption::create([
            'code' => $code,
            'name' => $request->input('name'),
            'image_path' => $imagePath,
            'category' => $request->input('category'),
            'unit_price' => $request->input('unit_price'),
            'is_active' => $request->boolean('is_active', true),
            'is_default' => $request->boolean('is_default', false),
        ]);

        return redirect()->route('admin.packages.index')->with('success', 'เพิ่มเมนูเสริมเรียบร้อยแล้ว ✅');
    }

    public function updateAddon(Request $request, $id)
    {
        $addon = AddonOption::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:detergent,softener,service',
            'unit_price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'nullable|boolean',
            'is_default' => 'nullable|boolean',
        ]);

        if ($request->boolean('is_default')) {
            $category = $request->input('category');
            if (in_array($category, ['detergent', 'softener'], true)) {
                AddonOption::where('category', $category)->where('id', '!=', $addon->id)->update(['is_default' => false]);
            }
        }

        $imagePath = $addon->image_path;
        if ($request->hasFile('image')) {
            if (!empty($addon->image_path)) {
                Storage::disk('public')->delete($addon->image_path);
            }
            $imagePath = $request->file('image')->store('addons', 'public');
        }

        $addon->update([
            'name' => $request->input('name'),
            'image_path' => $imagePath,
            'category' => $request->input('category'),
            'unit_price' => $request->input('unit_price'),
            'is_active' => $request->boolean('is_active', false),
            'is_default' => $request->boolean('is_default', false),
        ]);

        return redirect()->route('admin.packages.index')->with('success', 'อัปเดตเมนูเสริมเรียบร้อยแล้ว ✅');
    }

    public function destroyAddon($id)
    {
        $addon = AddonOption::findOrFail($id);

        $isUsedByPackage = Package::where('default_detergent_code', $addon->code)->exists();
        if ($isUsedByPackage) {
            return redirect()->route('admin.packages.index')->with('error', 'ไม่สามารถลบได้ เพราะมีแพ็กเกจที่ตั้งเมนูนี้เป็นค่าเริ่มต้น');
        }

        if (!empty($addon->image_path)) {
            Storage::disk('public')->delete($addon->image_path);
        }

        $addon->delete();

        return redirect()->route('admin.packages.index')->with('success', 'ลบเมนูเสริมเรียบร้อยแล้ว 🗑️');
    }
    // ==========================================
    // 👥 โซนจัดการลูกค้า (Customer)
    // ==========================================
    public function customers()
    {
        // ดึงเฉพาะคนที่มี role เป็น customer
        $customers = User::where('role', 'customer')->orderBy('created_at', 'desc')->get();
        return view('admin.customers', compact('customers'));
    }

    public function storeCustomer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => 'customer',
        ]);

        return redirect()->route('admin.customers.index')->with('success', 'เพิ่มบัญชีลูกค้าใหม่เรียบร้อยแล้ว!');
    }

    public function updateCustomer(Request $request, $id)
    {
        $customer = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $customer->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        
        // อัปเดตรหัสผ่านเฉพาะตอนที่พิมพ์เข้ามาใหม่
        if ($request->filled('password')) {
            $customer->password = Hash::make($request->password);
        }
        $customer->save();

        return redirect()->route('admin.customers.index')->with('success', 'อัปเดตข้อมูลลูกค้าเรียบร้อยแล้ว!');
    }

    public function destroyCustomer($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('admin.customers.index')->with('success', 'ลบข้อมูลลูกค้าออกจากระบบแล้ว 🗑️');
    }


    // ==========================================
    // 👨‍💼 โซนจัดการพนักงานและแอดมิน (Staff & Admin)
    // ==========================================
    public function staff()
    {
        // ดึงคนที่เป็น admin หรือ staff
        $staff = User::whereIn('role', ['admin', 'staff'])->orderBy('role')->get();
        return view('admin.staff', compact('staff'));
    }

    public function storeStaff(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,staff' // ต้องเลือกว่าจะเป็นแอดมินหรือพนักงาน
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.staff.index')->with('success', 'เพิ่มพนักงานเข้าสู่ระบบเรียบร้อยแล้ว!');
    }

    public function updateStaff(Request $request, $id)
    {
        $staff = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $staff->id,
            'role' => 'required|in:admin,staff'
        ]);

        $staff->name = $request->name;
        $staff->email = $request->email;
        $staff->role = $request->role;

        if ($request->filled('password')) {
            $staff->password = Hash::make($request->password);
        }
        $staff->save();

        return redirect()->route('admin.staff.index')->with('success', 'อัปเดตข้อมูลพนักงานเรียบร้อยแล้ว!');
    }

    public function destroyStaff($id)
    {
        $staff = User::findOrFail($id);
        
        // 🚨 ป้องกันไม่ให้แอดมินกดลบบัญชีตัวเองตอนที่กำลังใช้งานอยู่
        if (Auth::id() == $staff->id) {
            return redirect()->route('admin.staff.index')->with('error', 'ไม่สามารถลบบัญชีของตัวเองได้! ❌');
        }

        $staff->delete();
        return redirect()->route('admin.staff.index')->with('success', 'ลบข้อมูลพนักงานออกจากระบบแล้ว 🗑️');
    }
}