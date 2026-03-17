@extends('layouts.admin')

@section('title', 'จัดการออเดอร์ - Washly Admin')
@section('page_title', 'จัดการออเดอร์')

@section('content')
    <div class="space-y-6 pb-10">

        {{-- หัวข้อและปุ่มรีเฟรช --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div
                    class="w-12 h-12 rounded-2xl washly-card shadow-sm flex items-center justify-center border border-sky-100 dark:border-slate-700">
                    <i class="fa-solid fa-list-check text-2xl text-sky-500"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold washly-shell">จัดการออเดอร์</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">อัปเดตสถานะ ตรวจสลิป และจ่ายงานให้คนขับ</p>
                </div>
            </div>

            <button onclick="window.location.reload()"
                class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-rotate-right"></i> รีเฟรชข้อมูล
            </button>
        </div>

        @if (session('success'))
            <div
                class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 p-4 rounded-xl shadow-sm flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-emerald-500 text-xl"></i>
                <p class="font-medium text-emerald-700 dark:text-emerald-400">{{ session('success') }}</p>
            </div>
        @endif

        {{-- ตารางออเดอร์ --}}
        <div
            class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div
                class="p-5 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/50 flex justify-between items-center">
                <h2 class="text-lg font-bold washly-shell flex items-center gap-2">
                    <i class="fa-solid fa-clipboard-list text-sky-500"></i> รายการออเดอร์ทั้งหมด
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead
                        class="bg-slate-50 dark:bg-slate-800/50 text-slate-600 dark:text-slate-300 border-b border-slate-200 dark:border-slate-700">
                        <tr>
                            <th class="px-6 py-4 font-semibold">ออเดอร์</th>
                            <th class="px-6 py-4 font-semibold">ลูกค้า</th>
                            <th class="px-6 py-4 font-semibold">รายการ/ชำระเงิน</th>
                            <th class="px-6 py-4 font-semibold text-center">พนักงานขับรถ</th> {{-- 👈 คอลัมน์ใหม่ --}}
                            <th class="px-6 py-4 font-semibold text-center">สถานะปัจจุบัน</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50 washly-shell">
                        @forelse($orders as $order)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">

                                {{-- 1. เลขออเดอร์ --}}
                                <td class="px-6 py-4">
                                    <p class="font-bold text-sky-600 dark:text-sky-400">{{ $order->order_number }}</p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ \Carbon\Carbon::parse($order->created_at)->addYears(543)->format('d/m/Y H:i') }}
                                    </p>
                                </td>

                                {{-- 2. ลูกค้า --}}
                                <td class="px-6 py-4">
                                    <p class="font-bold">{{ $order->user->fullname ?? 'ไม่ระบุ' }}</p>
                                    <p class="text-xs text-gray-500 mt-1"><i class="fa-solid fa-phone mr-1"></i>
                                        {{ $order->user->phone ?? '-' }}</p>

                                    @if ($order->pickup_latitude && $order->pickup_longitude)
                                        @php
                                            $shopLat = config('washly.shop.latitude', env('SHOP_LATITUDE'));
                                            $shopLng = config('washly.shop.longitude', env('SHOP_LONGITUDE'));
                                        @endphp
                                        <div class="mt-2">
                                            @if($shopLat && $shopLng)
                                                <a href="https://www.google.com/maps/dir/?api=1&origin={{ $shopLat }},{{ $shopLng }}&destination={{ $order->pickup_latitude }},{{ $order->pickup_longitude }}&travelmode=driving"
                                                    target="_blank"
                                                    class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-100 dark:bg-blue-900/30 dark:border-blue-800 dark:text-blue-400 px-3 py-1.5 rounded-lg text-[11px] font-medium transition">
                                                    <i class="fa-solid fa-map-location-dot"></i> นำทางไปรับ
                                                </a>
                                            @elseif($order->pickup_latitude && $order->pickup_longitude)
                                                <a href="https://maps.google.com/?q={{ $order->pickup_latitude }},{{ $order->pickup_longitude }}"
                                                    target="_blank"
                                                    class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-100 dark:bg-blue-900/30 dark:border-blue-800 dark:text-blue-400 px-3 py-1.5 rounded-lg text-[11px] font-medium transition">
                                                    <i class="fa-solid fa-map-location-dot"></i> แผนที่
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </td>

                                {{-- 3. แพ็กเกจและการชำระเงิน --}}
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-gray-700 dark:text-gray-200">{{ $order->package->name ?? '-' }}
                                    </p>
                                    <p class="text-sm font-bold text-sky-500 mt-1 mb-2">
                                        ฿{{ number_format($order->total_price) }}</p>

                                    <div class="text-[11px] text-gray-500 mb-2">
                                        วิธีชำระ:
                                        <span
                                            class="font-semibold {{ ($order->payment_method ?? 'transfer') === 'cash' ? 'text-amber-500' : 'text-blue-500' }}">
                                            {{ ($order->payment_method ?? 'transfer') === 'cash' ? 'เงินสด' : 'โอน/สแกน QR' }}
                                        </span>
                                    </div>

                                    {{-- ปุ่มจัดการสลิป/เงินสด --}}
                                    <div class="flex items-center gap-1">
                                        @if ($order->payment_status == 'paid')
                                            @if (($order->payment_method ?? 'transfer') === 'transfer' && $order->payment_slip)
                                                <button type="button"
                                                    onclick="showSlip('{{ asset('storage/' . $order->payment_slip) }}')"
                                                    class="inline-flex items-center gap-1 text-[11px] bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300 px-2 py-1 rounded hover:bg-slate-200 transition">
                                                    <i class="fa-solid fa-receipt"></i> ดูสลิป
                                                </button>
                                            @endif
                                            <span class="text-[11px] text-emerald-500 font-bold ml-1"><i
                                                    class="fa-solid fa-circle-check"></i> ชำระแล้ว</span>

                                        @elseif($order->payment_status == 'pending_cash')
                                            <span
                                                class="inline-flex items-center gap-1 text-[11px] bg-amber-50 text-amber-600 border border-amber-200 dark:bg-amber-900/30 px-2 py-1 rounded">
                                                <i class="fa-solid fa-hand-holding-dollar"></i> รอเก็บเงินสด
                                            </span>
                                            <form action="{{ route('admin.orders.confirm_cash', $order->id) }}" method="POST"
                                                class="m-0 inline">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center text-[11px] bg-emerald-100 text-emerald-700 w-6 h-6 rounded hover:bg-emerald-200 transition ml-1"
                                                    title="ยืนยันรับเงินแล้ว">
                                                    <i class="fa-solid fa-check"></i>
                                                </button>
                                            </form>

                                        @elseif($order->payment_status == 'reviewing' && $order->payment_slip)
                                            <button type="button"
                                                onclick="showSlip('{{ asset('storage/' . $order->payment_slip) }}')"
                                                class="inline-flex items-center gap-1 text-[11px] bg-sky-100 text-sky-700 px-2 py-1 rounded hover:bg-sky-200 transition">
                                                <i class="fa-solid fa-image"></i> ตรวจสลิป
                                            </button>
                                            <form action="{{ route('admin.orders.approve_payment', $order->id) }}" method="POST"
                                                class="m-0 inline">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center text-[11px] bg-emerald-100 text-emerald-700 w-6 h-6 rounded hover:bg-emerald-200 transition ml-1"
                                                    title="อนุมัติ"><i class="fa-solid fa-check"></i></button>
                                            </form>
                                            <form action="{{ route('admin.orders.reject_slip', $order->id) }}" method="POST"
                                                class="m-0 inline">
                                                @csrf
                                                <button type="button" onclick="confirmRejectSlip(this.closest('form'))"
                                                    class="inline-flex items-center justify-center text-[11px] bg-rose-100 text-rose-700 w-6 h-6 rounded hover:bg-rose-200 transition ml-1"
                                                    title="ปฏิเสธ"><i class="fa-solid fa-xmark"></i></button>
                                            </form>
                                        @else
                                            <span
                                                class="text-[11px] text-gray-400 bg-gray-100 px-2 py-1 rounded dark:bg-slate-700"><i
                                                    class="fa-solid fa-hourglass-half"></i> รอชำระเงิน</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- 4. 🚚 จ่ายงานให้คนขับ (Assign Driver) --}} <td class="px-6 py-4 text-center">
                                    @if($order->driver_id)
                                        {{-- ถ้ามีคนขับแล้ว โชว์ชื่อคนขับ --}}
                                        <div
                                            class="inline-flex items-center gap-2 bg-sky-50 dark:bg-sky-900/30 border border-sky-100 dark:border-sky-800 px-3 py-1.5 rounded-xl">
                                            <div
                                                class="w-6 h-6 rounded-full bg-sky-500 text-white flex items-center justify-center text-[10px] font-bold">
                                                {{ mb_substr($order->driver->fullname ?? 'D', 0, 1) }}
                                            </div>
                                            <span
                                                class="text-xs font-semibold text-sky-700 dark:text-sky-400">{{ $order->driver->fullname ?? 'ไม่ทราบชื่อ' }}</span>
                                        </div>
                                    @else
                                        {{-- ถ้ายังไม่มีคนขับ (โชว์ช่องให้เลือกตราบใดที่ยังไม่ยกเลิกหรือเสร็จสิ้น) --}}
                                        @if(!in_array($order->status, ['completed', 'cancelled']))
                                            <form action="{{ route('admin.orders.assign_driver', $order->id) }}" method="POST" class="m-0 flex flex-col items-center gap-2" onsubmit="this.querySelector('button[type=submit]').disabled = true; this.querySelector('button[type=submit]').innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i> รอสักครู่...';">
                                                @csrf
                                                <select name="driver_id" required
                                                    class="text-xs border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 rounded-lg py-1.5 px-2 outline-none focus:ring-1 focus:ring-sky-500 w-32">
                                                    <option value="" disabled selected>-- เลือกคนขับ --</option>
                                                    @foreach($drivers as $driver)
                                                        <option value="{{ $driver->id }}">{{ $driver->fullname }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit"
                                                    class="text-[10px] bg-sky-500 text-white px-3 py-1 rounded-lg w-full hover:bg-sky-600 shadow-sm"><i
                                                        class="fa-solid fa-motorcycle mr-1"></i> จ่ายงาน</button>
                                            </form>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    @endif
                                    </td>

                                    {{-- 5. สถานะปัจจุบัน (อัปเดต) --}}
                            <td class="px-6 py-4 text-center">
                                @if($order->status === 'cancelled')
                                    {{-- ถ้าถูกยกเลิกแล้ว ล็อกตายตัวเลย --}}
                                    <div class="bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 text-rose-600 dark:text-rose-400 px-3 py-2 rounded-xl text-left">
                                        <p class="font-bold text-xs mb-1"><i class="fa-solid fa-ban"></i> ถูกยกเลิกแล้ว</p>
                                        <p class="text-[10px] opacity-90">เหตุผล: {{ $order->cancel_reason ?? 'ไม่ได้ระบุเหตุผล' }}</p>
                                    </div>
                                @else
                                    {{-- ถ้ายังไม่ยกเลิก โชว์ Dropdown ปกติ --}}
                                    @php
                                        $statusLabels = [
                                            'pending' => 'รออนุมัติ',
                                            'pending_pickup' => 'รอรับผ้า',
                                            'picking_up' => 'กำลังไปรับ',
                                            'picked_up' => 'รับผ้ามาแล้ว', // 🚨 เพิ่มบรรทัดนี้เข้ามา
                                            'processing' => 'กำลังซัก/อบ',
                                            'washing_completed' => 'ซักเสร็จ/รอส่ง',
                                            'delivering' => 'กำลังไปส่ง',
                                            'completed' => 'เสร็จสิ้น',
                                        ];
                                    @endphp
                                    <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="flex flex-col items-center gap-2 m-0" onsubmit="this.querySelector('button[type=submit]').disabled = true; this.querySelector('button[type=submit]').innerHTML = 'กำลังบันทึก...';">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="text-xs border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 font-semibold rounded-lg py-1.5 px-2 outline-none focus:border-sky-500 w-32 text-center">
                                            @foreach ($statusLabels as $key => $label)
                                                <option value="{{ $key }}" {{ $order->status == $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="text-[10px] bg-slate-200 hover:bg-slate-300 text-slate-700 dark:bg-slate-600 dark:text-slate-300 dark:hover:bg-slate-500 px-3 py-1 rounded-lg w-full transition">บันทึกสถานะ</button>
                                    </form>

                                    {{-- ปุ่มยกเลิกแยกต่างหาก --}}
                                    <div class="mt-2 pt-2 border-t border-slate-100 dark:border-slate-700 w-full">
                                        <button type="button" onclick="confirmCancelOrder({{ $order->id }}, '{{ $order->order_number }}')" class="text-[10px] text-rose-500 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 dark:bg-rose-900/30 dark:hover:bg-rose-800/50 w-full py-1.5 rounded-lg transition-colors font-medium flex items-center justify-center gap-1">
                                            <i class="fa-solid fa-ban"></i> ยกเลิกออเดอร์
                                        </button>
                                    </div>

                                    {{-- ฟอร์มซ่อนสำหรับส่งค่ายกเลิก --}}
                                    <form id="cancel-form-{{ $order->id }}" action="{{ route('admin.orders.cancel', $order->id) }}" method="POST" class="hidden">
                                        @csrf
                                        <input type="hidden" name="cancel_reason" id="cancel-reason-{{ $order->id }}">
                                    </form>
                                @endif
                            </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-12 text-center text-gray-400">
                                    <div
                                        class="inline-block p-5 rounded-full bg-slate-50 dark:bg-slate-800 mb-4 border border-slate-100 dark:border-slate-700">
                                        <i class="fa-solid fa-inbox text-4xl text-slate-300 dark:text-slate-600"></i>
                                    </div>
                                    <p class="text-lg font-medium washly-shell">ยังไม่มีออเดอร์ในระบบ</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    // 1. ฟังก์ชันดูสลิป
    function showSlip(imageUrl) {
        const isDark = document.documentElement.classList.contains('dark');
        Swal.fire({
            title: 'หลักฐานการโอนเงิน',
            imageUrl: imageUrl,
            imageAlt: 'สลิปโอนเงิน',
            confirmButtonText: 'ปิดหน้าต่าง',
            confirmButtonColor: '#0ea5e9', // sky-500
            background: isDark ? '#1e293b' : '#ffffff',
            color: isDark ? '#f8fafc' : '#1e293b',
            customClass: {
                popup: 'rounded-3xl',
                image: 'rounded-xl max-h-[60vh] object-contain border border-slate-200 dark:border-slate-700'
            }
        });
    }

    // 2. ฟังก์ชันปฏิเสธสลิป
    function confirmRejectSlip(form) {
        const isDark = document.documentElement.classList.contains('dark');
        Swal.fire({
            title: 'ปฏิเสธสลิปใบนี้?',
            text: "ลูกค้าจะต้องอัปโหลดสลิปเข้ามาใหม่",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'ใช่, ปฏิเสธ',
            cancelButtonText: 'ยกเลิก',
            background: isDark ? '#1e293b' : '#ffffff',
            color: isDark ? '#f8fafc' : '#1e293b',
            customClass: { popup: 'rounded-3xl' }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        })
    }

    // 3. ฟังก์ชันยกเลิกออเดอร์ (พร้อมบังคับใส่เหตุผล)
    function confirmCancelOrder(orderId, orderNumber) {
        const isDark = document.documentElement.classList.contains('dark');
        Swal.fire({
            title: 'ยกเลิกออเดอร์ ' + orderNumber + ' ?',
            text: "การยกเลิกจะไม่สามารถกู้คืนได้ กรุณาระบุเหตุผลเพื่อแจ้งลูกค้า",
            icon: 'warning',
            input: 'textarea',
            inputPlaceholder: 'พิมพ์เหตุผลที่ยกเลิกออเดอร์...',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'ยืนยันการยกเลิก',
            cancelButtonText: 'ปิดหน้าต่าง',
            background: isDark ? '#1e293b' : '#ffffff',
            color: isDark ? '#f8fafc' : '#1e293b',
            customClass: { popup: 'rounded-3xl' },
            inputValidator: (value) => {
                if (!value.trim()) {
                    return 'กรุณาระบุเหตุผลการยกเลิกด้วยครับ!'
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // เอาเหตุผลไปใส่ในฟอร์มที่ซ่อนไว้ แล้วกดยืนยันฟอร์ม
                document.getElementById('cancel-reason-' + orderId).value = result.value;
                document.getElementById('cancel-form-' + orderId).submit();
            }
        });
    }
</script>

{{-- 4. ตัวดักจับ Error และเด้งเตือนสีแดง (เช่น ลืมเลือกคนขับ) --}}
@if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isDark = document.documentElement.classList.contains('dark');
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด!',
                text: '{{ session("error") }}',
                confirmButtonText: 'ตกลง',
                confirmButtonColor: '#ef4444',
                background: isDark ? '#1e293b' : '#ffffff',
                color: isDark ? '#f8fafc' : '#1e293b',
                customClass: { popup: 'rounded-3xl' }
            });
        });
    </script>
@endif
@endsection