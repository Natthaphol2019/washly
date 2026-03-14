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
    public function store(Request $request)
    {
        $now = \Carbon\Carbon::now()->timezone('Asia/Bangkok');
        $isClosedToday = $now->format('H:i') >= '17:00';

        // ป้องกันคนแฮกยิงข้อมูลมาตอนร้านปิด
        if ($request->input('pickup_date') === 'today' && $isClosedToday) {
            return back()->withErrors(['closed' => 'ขออภัยครับ ปิดรับจองคิวสำหรับวันนี้แล้ว กรุณาเลือกจองเป็น "พรุ่งนี้" แทนนะครับ'])->withInput();
        }

        // 1. ตรวจสอบข้อมูล
        $request->validate([
            'pickup_date' => 'required|in:today,tomorrow',
            'package_id' => 'required|exists:packages,id',
            'time_slot_id' => 'required|exists:time_slots,id',
            'pickup_address' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'payment_method' => 'required|in:transfer,cash',
            'customer_note' => 'nullable|string|max:1000',
            'use_customer_detergent' => 'nullable|boolean',
            'use_customer_softener' => 'nullable|boolean',
            'addons' => 'nullable|array',
            'addons.*.code' => 'nullable|string',
            'addons.*.qty' => 'nullable|integer|min:1|max:10',
            'extra_dry_qty' => 'nullable|array',
        ]);

        $user = Auth::user();
        $package = Package::findOrFail($request->package_id);
        $catalog = $this->addonCatalog();
        $selectedAddons = [];
        $addonTotal = 0;
        $useCustomerDetergent = $request->boolean('use_customer_detergent');
        $useCustomerSoftener = $request->boolean('use_customer_softener');

        // จัดการเรื่อง Addons
        foreach ($request->input('addons', []) as $addonInput) {
            $code = (string) ($addonInput['code'] ?? '');
            $qty = (int) ($addonInput['qty'] ?? 0);

            if (!isset($catalog[$code]) || $qty < 1) {
                continue;
            }

            $addon = $catalog[$code];

            if ($useCustomerDetergent && $addon['category'] === 'detergent')
                continue;
            if ($useCustomerSoftener && $addon['category'] === 'softener')
                continue;

            $lineTotal = $addon['price'] * $qty;
            $addonTotal += $lineTotal;

            $selectedAddons[] = [
                'code' => $addon['code'],
                'name' => $addon['name'],
                'category' => $addon['category'],
                'unit_price' => $addon['price'],
                'qty' => $qty,
                'line_total' => $lineTotal,
            ];
        }

        // 🌟 เพิ่มระบบบวกค่า "อบผ้าเพิ่ม" ลงในบิลอัตโนมัติ
        $extraDryQty = (int) $request->input("extra_dry_qty.{$package->id}", 0);
        if ($extraDryQty > 0) {
            $extraDryTotal = $extraDryQty * 10; // ครั้งละ 10 บาท
            $addonTotal += $extraDryTotal;

            $selectedAddons[] = [
                'code' => 'extra_dry',
                'name' => 'อบผ้าเพิ่ม (' . ($extraDryQty * 10) . ' นาที)',
                'category' => 'service',
                'unit_price' => 10,
                'qty' => $extraDryQty,
                'line_total' => $extraDryTotal,
                'auto_selected' => false,
            ];
        }

        $hasDetergent = collect($selectedAddons)->contains(fn($item) => ($item['category'] ?? null) === 'detergent');
        $hasSoftener = collect($selectedAddons)->contains(fn($item) => ($item['category'] ?? null) === 'softener');

        if (!$useCustomerDetergent && !$hasDetergent) {
            $defaultCode = $this->getDefaultDetergentCode($package, $catalog);
            if ($defaultCode && isset($catalog[$defaultCode])) {
                $defaultAddon = $catalog[$defaultCode];
                $lineTotal = (float) $defaultAddon['price'];
                $addonTotal += $lineTotal;

                $selectedAddons[] = [
                    'code' => $defaultAddon['code'],
                    'name' => $defaultAddon['name'],
                    'category' => $defaultAddon['category'],
                    'unit_price' => $defaultAddon['price'],
                    'qty' => 1,
                    'line_total' => $lineTotal,
                    'auto_selected' => true,
                ];
            } else {
                return back()->withErrors(['addons' => 'ยังไม่มีเมนูน้ำยาซักในระบบ กรุณาแจ้งแอดมิน'])->withInput();
            }
        }

        if (!$useCustomerSoftener && !$hasSoftener) {
            $defaultSoftenerCode = $this->getDefaultAddonCodeByCategory($catalog, 'softener');
            if ($defaultSoftenerCode && isset($catalog[$defaultSoftenerCode])) {
                $defaultSoftener = $catalog[$defaultSoftenerCode];
                $lineTotal = (float) $defaultSoftener['price'];
                $addonTotal += $lineTotal;

                $selectedAddons[] = [
                    'code' => $defaultSoftener['code'],
                    'name' => $defaultSoftener['name'],
                    'category' => $defaultSoftener['category'],
                    'unit_price' => $defaultSoftener['price'],
                    'qty' => 1,
                    'line_total' => $lineTotal,
                    'auto_selected' => true,
                ];
            }
        }

        // 📍 รับพิกัด
        $customerLat = $request->input('latitude', $user->latitude);
        $customerLng = $request->input('longitude', $user->longitude);

        $subtotal = (float) $package->price;

        // ==========================================
        // 🛵 🌟 คำนวณค่ารับ-ส่งผ้าแบบขั้นบันได
        // ==========================================
        $deliveryFee = 0;
        $deliveryDistance = $user->delivery_distance;

        if ($deliveryDistance !== null) {
            if ($deliveryDistance > 1.5) {
                // เกิน 1.5 กม. ค่อยคิดเงิน
                $extraDistance = $deliveryDistance - 1.5;
                $deliveryFee = ceil($extraDistance) * 20;
            }
        }

        // นำค่าซัก + ค่าน้ำยา + ค่าจัดส่ง มารวมกัน
        $grandTotal = $subtotal + $addonTotal + $deliveryFee;

        $paymentMethod = $request->input('payment_method');
        $paymentStatus = $paymentMethod === 'cash' ? 'pending_cash' : 'unpaid';

        // คำนวณวันที่รับผ้าจริงๆ จากตัวเลือก
        $actualPickupDate = $request->input('pickup_date') === 'today'
            ? \Carbon\Carbon::now()->timezone('Asia/Bangkok')->format('Y-m-d')
            : \Carbon\Carbon::now()->timezone('Asia/Bangkok')->addDay()->format('Y-m-d');

        // สร้างออเดอร์
        $orderNumber = 'ORD-' . date('Ymd') . '-' . rand(1000, 9999);
        $order = new Order();
        $order->order_number = $orderNumber;
        $order->user_id = $user->id;
        $order->package_id = $request->package_id;
        $order->time_slot_id = $request->time_slot_id;
        $order->pickup_date = $actualPickupDate;
        $order->pickup_address = $request->pickup_address;
        $order->customer_note = $request->customer_note;

        // บันทึกพิกัด และ ระยะทาง
        $order->pickup_latitude = $customerLat;
        $order->pickup_longitude = $customerLng;
        $order->pickup_map_link = "https://maps.google.com/?q={$customerLat},{$customerLng}";
        $order->distance = $deliveryDistance;
        $order->use_customer_detergent = $useCustomerDetergent;
        $order->use_customer_softener = $useCustomerSoftener;
        $order->subtotal = $subtotal;
        $order->addon_total = $addonTotal;
        $order->delivery_fee = $deliveryFee; // 🌟 เซฟยอดค่าจัดส่งลงบิล (ถ้าตาราง orders มีฟิลด์นี้)
        $order->selected_addons = $selectedAddons;
        $order->total_price = $grandTotal; // 🌟 ยอดสุทธิถูกบวกค่าส่งแล้ว!
        $order->status = 'pending';
        $order->payment_method = $paymentMethod;
        $order->payment_status = $paymentStatus;
        $order->save();

        $paymentMethodLabel = $paymentMethod === 'cash' ? 'เงินสดปลายทาง' : 'โอน/สแกน QR';

        $user->notify(new SystemNotification(
            'จองคิวสำเร็จ',
            'ออเดอร์ ' . $orderNumber . ' ถูกสร้างเรียบร้อยแล้ว (' . $paymentMethodLabel . ')',
            route('customer.orders'),
            'success'
        ));

        $admins = User::whereIn('role', ['admin', 'staff'])->get();
        Notification::send($admins, new SystemNotification(
            'มีออเดอร์ใหม่',
            'ลูกค้า ' . ($user->fullname ?? 'ไม่ระบุชื่อ') . ' จองคิวใหม่หมายเลข ' . $orderNumber,
            route('admin.orders.index'),
            'info'
        ));

        // อัปเดตพิกัดลูกค้าใน Profile
        if ($customerLat && $customerLng) {
            $user->update([
                'latitude' => $customerLat,
                'longitude' => $customerLng,
                'map_link' => "https://maps.google.com/?q={$customerLat},{$customerLng}"
            ]);
        }

        return redirect()->route('customer.main')->with('success_order', 'จองคิวสำเร็จ! หมายเลขออเดอร์ของคุณคือ ' . $orderNumber);
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

    public function switchToCash($id)
    {
        $order = Order::findOrFail($id);

        if ($order->user_id !== Auth::id()) {
            return redirect()->route('customer.orders')->with('error', 'ไม่สามารถแก้ไขวิธีชำระเงินของออเดอร์นี้ได้');
        }

        if ($order->payment_method !== 'transfer') {
            return redirect()->route('customer.orders')->with('info', 'ออเดอร์นี้ถูกตั้งเป็นเงินสดปลายทางไว้แล้ว');
        }

        if ($order->payment_status !== 'unpaid' || $order->payment_slip !== null) {
            return redirect()->route('customer.orders')->with('error', 'เปลี่ยนเป็นเงินสดปลายทางไม่ได้ เพราะออเดอร์นี้มีการชำระเงินหรือส่งสลิปไปแล้ว');
        }

        $order->payment_method = 'cash';
        $order->payment_status = 'pending_cash';
        $order->save();

        $customer = Auth::user();
        $customer->notify(new SystemNotification(
            'เปลี่ยนวิธีชำระเงินแล้ว',
            'ออเดอร์ ' . $order->order_number . ' ถูกเปลี่ยนเป็นชำระเงินสดปลายทางเรียบร้อยแล้ว',
            route('customer.orders'),
            'info'
        ));

        $admins = User::whereIn('role', ['admin', 'staff'])->get();
        Notification::send($admins, new SystemNotification(
            'ลูกค้าเปลี่ยนเป็นเงินสดปลายทาง',
            'ออเดอร์ ' . $order->order_number . ' เปลี่ยนวิธีชำระเงินเป็นเงินสดปลายทางแล้ว',
            route('admin.orders.index'),
            'warning'
        ));

        return redirect()->route('customer.orders')->with('success', 'เปลี่ยนเป็นชำระเงินปลายทางเรียบร้อยแล้ว');
    }
}