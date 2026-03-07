<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\TimeSlot;
use App\Models\Order; // 👈 อย่าลืมเรียกใช้ Model Order ด้วยนะครับ
use App\Models\AddonOption;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

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

    // (ฟังก์ชัน showBookingForm เดิมของซี ปล่อยไว้เหมือนเดิมครับ)
    public function showBookingForm()
    {
        $packages = Package::all();
        $timeSlots = TimeSlot::all();
        $catalog = $this->addonCatalog();
        $addons = array_values($catalog);
        $detergentAddons = array_values(array_filter($addons, fn ($item) => ($item['category'] ?? null) === 'detergent'));
        $softenerAddons = array_values(array_filter($addons, fn ($item) => ($item['category'] ?? null) === 'softener'));
        $serviceAddons = array_values(array_filter($addons, fn ($item) => ($item['category'] ?? null) === 'service'));

        $packageDefaultAddonMap = [];
        foreach ($packages as $package) {
            $defaultCode = $this->getDefaultDetergentCode($package, $catalog);
            $packageDefaultAddonMap[$package->id] = $defaultCode ? ($catalog[$defaultCode]['name'] ?? null) : null;
        }

        return view('customer.book', compact('packages', 'timeSlots', 'addons', 'detergentAddons', 'softenerAddons', 'serviceAddons', 'packageDefaultAddonMap'));
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
            'payment_method' => 'required|in:transfer,cash',
            'use_customer_detergent' => 'nullable|boolean',
            'use_customer_softener' => 'nullable|boolean',
            'addons' => 'nullable|array',
            'addons.*.code' => 'required|string',
            'addons.*.qty' => 'required|integer|min:1|max:10',
        ]);

        $user = Auth::user();
        $package = Package::findOrFail($request->package_id);
        $catalog = $this->addonCatalog();
        $selectedAddons = [];
        $addonTotal = 0;
        $useCustomerDetergent = $request->boolean('use_customer_detergent');
        $useCustomerSoftener = $request->boolean('use_customer_softener');

        foreach ($request->input('addons', []) as $addonInput) {
            $code = (string) ($addonInput['code'] ?? '');
            $qty = (int) ($addonInput['qty'] ?? 0);

            if (!isset($catalog[$code]) || $qty < 1) {
                continue;
            }

            $addon = $catalog[$code];

            if ($useCustomerDetergent && $addon['category'] === 'detergent') {
                continue;
            }

            if ($useCustomerSoftener && $addon['category'] === 'softener') {
                continue;
            }

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

        $hasDetergent = collect($selectedAddons)->contains(function ($item) {
            return ($item['category'] ?? null) === 'detergent';
        });

        $hasSoftener = collect($selectedAddons)->contains(function ($item) {
            return ($item['category'] ?? null) === 'softener';
        });

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
                return back()->withErrors([
                    'addons' => 'ยังไม่มีเมนูน้ำยาซักในระบบ กรุณาแจ้งแอดมินเพื่อเพิ่มข้อมูลก่อนรับจอง',
                ])->withInput();
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

        $subtotal = (float) $package->price;
        $grandTotal = $subtotal + $addonTotal;
        $paymentMethod = $request->input('payment_method');
        $paymentStatus = $paymentMethod === 'cash' ? 'pending_cash' : 'unpaid';

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
        $order->use_customer_detergent = $useCustomerDetergent;
        $order->use_customer_softener = $useCustomerSoftener;

        // ใส่ราคาสุทธิ และสถานะเริ่มต้น
        $order->subtotal = $subtotal;
        $order->addon_total = $addonTotal;
        $order->selected_addons = $selectedAddons;
        $order->total_price = $grandTotal;
        $order->status = 'pending'; // สถานะรอมารับผ้า
        $order->payment_method = $paymentMethod;
        $order->payment_status = $paymentStatus;

        // บันทึกตู้ม! ลงตาราง orders
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

        // 4. เด้งกลับไปหน้าแรก (Dashboard) พร้อมส่งข้อความแจ้งเตือนไปบอก
        return redirect()->route('customer.main')->with('success_order', 'จองคิวสำเร็จ! หมายเลขออเดอร์ของคุณคือ ' . $orderNumber);
    }
}