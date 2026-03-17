<?php

namespace App\Http\Controllers;

use App\Models\AddonOption;
use App\Models\Order;
use App\Models\Package;
use App\Models\TimeSlot;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;

class BookingController extends Controller
{
    private function addonCatalog(): array
    {
        return AddonOption::where('is_active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->mapWithKeys(function ($addon) {
                return [
                    $addon->code => [
                        'code' => $addon->code,
                        'name' => $addon->name,
                        'image_path' => $addon->image_path,
                        'price' => (float) $addon->unit_price,
                        'category' => $addon->category,
                        'is_default' => (bool) $addon->is_default,
                    ],
                ];
            })
            ->toArray();
    }

    private function getDefaultDetergentCode(Package $package, array $catalog): ?string
    {
        $packageDefault = (string) ($package->default_detergent_code ?? '');
        if ($packageDefault !== '' && isset($catalog[$packageDefault]) && ($catalog[$packageDefault]['category'] ?? null) === 'detergent') {
            return $packageDefault;
        }

        foreach ($catalog as $code => $item) {
            if (($item['category'] ?? null) === 'detergent' && ($item['is_default'] ?? false) === true) {
                return $code;
            }
        }

        foreach ($catalog as $code => $item) {
            if (($item['category'] ?? null) === 'detergent') {
                return $code;
            }
        }

        return null;
    }

    private function getDefaultAddonCodeByCategory(array $catalog, string $category): ?string
    {
        foreach ($catalog as $code => $item) {
            if (($item['category'] ?? null) === $category && ($item['is_default'] ?? false) === true) {
                return $code;
            }
        }

        foreach ($catalog as $code => $item) {
            if (($item['category'] ?? null) === $category) {
                return $code;
            }
        }

        return null;
    }

    public function showBookingForm()
    {
        // 🌟 1. ดึงเวลาและวันที่สำหรับระบบ วันนี้/พรุ่งนี้
        $now = Carbon::now()->timezone('Asia/Bangkok');
        $isClosedToday = $now->format('H:i') >= '17:00'; // เช็กว่าเกิน 17:00 น. หรือยัง

        $today = clone $now;
        $tomorrow = clone $now->addDay();

        $packages = Package::where('is_active', true)->orderBy('id', 'asc')->get();
        $timeSlots = TimeSlot::orderBy('id')->get();

        $catalog = $this->addonCatalog();
        $addons = array_values($catalog);
        $detergentAddons = array_values(array_filter($addons, fn($item) => ($item['category'] ?? null) === 'detergent'));
        $softenerAddons = array_values(array_filter($addons, fn($item) => ($item['category'] ?? null) === 'softener'));
        $serviceAddons = array_values(array_filter($addons, fn($item) => ($item['category'] ?? null) === 'service'));

        $packageDefaultAddonMap = [];
        foreach ($packages as $package) {
            $defaultCode = $this->getDefaultDetergentCode($package, $catalog);
            $packageDefaultAddonMap[$package->id] = $defaultCode ? ($catalog[$defaultCode]['name'] ?? null) : null;
        }

        // 🌟 2. ดึงคิวของ "วันนี้"
        $todayQueues = Order::with(['user', 'timeSlot'])
            ->where(function ($q) use ($today) {
                // ออเดอร์ที่ระบุ pickup_date เป็นวันนี้ หรือ ออเดอร์เก่าที่ไม่มี pickup_date แต่สร้างวันนี้
                $q->whereDate('pickup_date', $today->format('Y-m-d'))
                    ->orWhere(function ($sub) use ($today) {
                    $sub->whereNull('pickup_date')->whereDate('created_at', $today->format('Y-m-d'));
                });
            })
            ->where('status', '!=', 'cancelled')
            ->orderBy('created_at', 'asc')
            ->get();

        // 🌟 3. ดึงคิวของ "พรุ่งนี้"
        $tomorrowQueues = Order::with(['user', 'timeSlot'])
            ->whereDate('pickup_date', $tomorrow->format('Y-m-d'))
            ->where('status', '!=', 'cancelled')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('customer.book', compact(
            'packages',
            'timeSlots',
            'addons',
            'detergentAddons',
            'softenerAddons',
            'serviceAddons',
            'packageDefaultAddonMap',
            'todayQueues',      // ส่งคิววันนี้ไป View
            'tomorrowQueues',   // ส่งคิวพรุ่งนี้ไป View
            'today',            // วันที่วันนี้ (ใช้แปลงไทยใน View)
            'tomorrow',         // วันที่พรุ่งนี้
            'isClosedToday'     // ส่งสถานะร้านไปใช้บล็อกปุ่มใน View
        ));
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

            // ยัดเป็นเมนูเสริมเนียนๆ ไปเลย
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
        $order->delivery_fee = $deliveryFee; 
        $order->selected_addons = $selectedAddons;
        $order->total_price = $grandTotal; 
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

        // 🌟 เปลี่ยนเส้นทางเมื่อจองสำเร็จ
        if ($paymentMethod === 'transfer') {
            return redirect()->route('customer.orders.pay', $order->id)
                ->with('success', 'จองคิวสำเร็จ! กรุณาชำระเงินและแนบสลิปเพื่อยืนยันออเดอร์ครับ');
        }

        return redirect()->route('customer.orders')
            ->with('success', 'จองคิวสำเร็จ! ไรเดอร์จะเข้าไปรับผ้าตามรอบเวลาที่คุณเลือกครับ');
    }
}