<?php

namespace App\Http\Controllers;

use App\Models\AddonOption;
use App\Models\Order;
use App\Models\Package;
use App\Models\TimeSlot;
use App\Models\User;
use App\Notifications\SystemNotification;
use App\Services\DeliveryDistanceService;
use Illuminate\Http\Request;
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

    public function showBookingForm()
    {
        // ดึงเฉพาะแพ็กเกจที่เปิดใช้งานอยู่ และเรียงราคาจากน้อยไปมาก
        $packages = Package::where('is_active', true)->orderBy('price', 'asc')->get();
        $timeSlots = TimeSlot::all();
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
        // 🌟 1. เพิ่มโค้ดส่วนนี้ เพื่อดึงคิวของวันนี้ทั้งหมด (ยกเว้นที่ถูกยกเลิก)
        $todayQueues = Order::with(['user', 'timeSlot'])
            ->whereDate('created_at', \Carbon\Carbon::today())
            ->where('status', '!=', 'cancelled')
            ->orderBy('created_at', 'asc') // เรียงตามลำดับเวลาที่จองเข้ามา
            ->get();
        return view('customer.book', compact('packages', 'timeSlots', 'addons', 'detergentAddons', 'softenerAddons', 'serviceAddons', 'packageDefaultAddonMap'));
    }

    public function store(Request $request)
    {
        // 1. ตรวจสอบข้อมูล
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'time_slot_id' => 'required|exists:time_slots,id',
            'wash_temp' => 'required|in:เย็น,อุ่น,ร้อน',
            'dry_temp' => 'required|in:อุ่น,ปานกลาง,ร้อน',
            'pickup_address' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
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

        // 📍 ลบระบบบวกเงินค่าส่งออกไปแล้ว รับแค่พิกัดเฉยๆ
        $customerLat = $request->input('latitude', $user->latitude);
        $customerLng = $request->input('longitude', $user->longitude);

        $subtotal = (float) $package->price;
        $grandTotal = $subtotal + $addonTotal; // ยอดรวมแบบยังไม่บวกค่าส่ง
        $paymentMethod = $request->input('payment_method');
        $paymentStatus = $paymentMethod === 'cash' ? 'pending_cash' : 'unpaid';

        // สร้างออเดอร์
        $orderNumber = 'ORD-' . date('Ymd') . '-' . rand(1000, 9999);
        $order = new Order();
        $order->order_number = $orderNumber;
        $order->user_id = $user->id;
        $order->package_id = $request->package_id;
        $order->time_slot_id = $request->time_slot_id;
        $order->wash_temp = $request->wash_temp;
        $order->dry_temp = $request->dry_temp;
        $order->pickup_address = $request->pickup_address;

        // บันทึกพิกัดให้แอดมินกดดู (แต่ไม่คำนวณเงินแล้ว)
        $order->pickup_latitude = $customerLat;
        $order->pickup_longitude = $customerLng;
        $order->pickup_map_link = "https://maps.google.com/?q={$customerLat},{$customerLng}";
        $order->distance = null; // ปล่อยว่างไว้

        $order->use_customer_detergent = $useCustomerDetergent;
        $order->use_customer_softener = $useCustomerSoftener;
        $order->subtotal = $subtotal;
        $order->addon_total = $addonTotal;
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

        if ($customerLat && $customerLng) {
            $user->update([
                'latitude' => $customerLat,
                'longitude' => $customerLng,
                'map_link' => "https://maps.google.com/?q={$customerLat},{$customerLng}"
            ]);
        }

        return redirect()->route('customer.main')->with('success_order', 'จองคิวสำเร็จ! หมายเลขออเดอร์ของคุณคือ ' . $orderNumber);
    }
}