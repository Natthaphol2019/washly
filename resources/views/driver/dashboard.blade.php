@extends('layouts.driver')
@section('title', 'หน้าแรก - ไรเดอร์')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:py-8 px-3 sm:px-6 space-y-8 sm:space-y-10 pb-20">
    
    {{-- 🌟 หัวข้อหลัก (Header Banner) สไตล์คลีน --}}
    <div class="relative overflow-hidden rounded-2xl sm:rounded-3xl bg-white dark:bg-slate-800 p-5 sm:p-8 shadow-sm border border-slate-200 dark:border-slate-700 transition-colors duration-500 flex justify-between items-center">
        <div class="relative z-10">
            <p class="text-gray-500 dark:text-gray-400 font-medium mb-1.5 flex items-center gap-2 text-xs sm:text-base transition-colors duration-500">
                <i class="fa-solid fa-sun text-amber-500 dark:text-yellow-400"></i> พร้อมลุยงานหรือยังครับ?
            </p>
            {{-- 🌟 เปลี่ยนเป็นสีดำ (gray-800) ในโหมดสว่าง และสีขาว (gray-100) ในโหมดมืด --}}
            <h1 class="text-2xl sm:text-4xl font-black tracking-tight text-gray-800 dark:text-gray-100 transition-colors duration-500">
                สวัสดี, คุณ {{ explode(' ', Auth::user()->fullname)[0] }}
            </h1>
        </div>
        <div class="hidden sm:flex relative z-10 w-20 h-20 bg-sky-50 dark:bg-slate-700/50 rounded-full items-center justify-center border border-sky-100 dark:border-slate-600 transition-colors duration-500">
            <i class="fa-solid fa-helmet-safety text-4xl text-sky-500 dark:text-sky-400"></i>
        </div>
        <div class="absolute -right-10 -top-10 w-32 sm:w-40 h-32 sm:h-40 bg-sky-100 dark:bg-sky-900/20 opacity-60 rounded-full blur-2xl"></div>
        <div class="absolute right-20 -bottom-10 w-24 sm:w-32 h-24 sm:h-32 bg-blue-100 dark:bg-blue-900/20 opacity-60 rounded-full blur-xl"></div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 p-3 sm:p-4 rounded-xl sm:rounded-2xl shadow-sm flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-lg sm:text-xl text-emerald-500"></i>
            <span class="text-sm sm:text-base font-bold text-emerald-700 dark:text-emerald-400">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 p-3 sm:p-4 rounded-xl sm:rounded-2xl shadow-sm flex items-center gap-3">
            <i class="fa-solid fa-circle-xmark text-lg sm:text-xl text-rose-500"></i>
            <span class="text-sm sm:text-base font-bold text-rose-700 dark:text-rose-400">{{ session('error') }}</span>
        </div>
    @endif

    {{-- ========================================== --}}
    {{-- 🛵 โซนที่ 1: งานที่ฉันรับไว้ (My Active Jobs) --}}
    {{-- ========================================== --}}
    <div>
        <h2 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-gray-100 mb-3 sm:mb-4 flex items-center gap-2 pl-1 sm:pl-2">
            <i class="fa-solid fa-motorcycle text-sky-500"></i> งานของฉันที่กำลังดำเนินการ ({{ $myOrders->count() }})
        </h2>
        
        <div class="bg-white dark:bg-slate-800 rounded-xl sm:rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            @if($myOrders->count() > 0)
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left whitespace-nowrap min-w-[650px] lg:min-w-[1000px]">
                        <thead class="bg-sky-50 dark:bg-slate-800/80 text-sky-800 dark:text-sky-300 border-b border-sky-100 dark:border-slate-700">
                            <tr>
                                <th class="px-3 py-3 lg:px-5 lg:py-4 font-bold text-xs lg:text-sm w-[15%] lg:w-[12%] min-w-[90px] lg:min-w-[120px]">ออเดอร์</th>
                                <th class="px-3 py-3 lg:px-5 lg:py-4 font-bold text-xs lg:text-sm w-[35%] lg:w-[40%] min-w-[180px] lg:min-w-[280px]">ลูกค้า / สถานที่รับส่ง</th>
                                <th class="px-3 py-3 lg:px-5 lg:py-4 font-bold text-xs lg:text-sm w-[20%] lg:w-[18%] min-w-[130px] lg:min-w-[180px]">การชำระเงิน</th>
                                <th class="px-3 py-3 lg:px-5 lg:py-4 font-bold text-center text-xs lg:text-sm w-[18%] lg:w-[18%] min-w-[140px] lg:min-w-[160px]">อัปเดตสถานะ</th>
                                <th class="px-3 py-3 lg:px-5 lg:py-4 font-bold text-center text-xs lg:text-sm w-[12%] lg:w-[12%] min-w-[110px] lg:min-w-[120px]">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                            @foreach($myOrders as $order)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                    
                                    {{-- 1. ข้อมูลออเดอร์ --}}
                                    <td class="px-3 py-3 lg:px-5 lg:py-4 align-top">
                                        <p class="font-bold text-sky-600 dark:text-sky-400 text-sm lg:text-base mb-1.5">#{{ $order->order_number }}</p>
                                        @if($order->status == 'pending_pickup')
                                            <span class="inline-block bg-yellow-100 text-yellow-700 text-[9px] lg:text-[10px] px-2 py-0.5 lg:px-2.5 lg:py-1 rounded-md font-bold"><i class="fa-solid fa-clock"></i> รอรับผ้า</span>
                                        @elseif($order->status == 'picking_up')
                                            <span class="inline-block bg-blue-100 text-blue-700 text-[9px] lg:text-[10px] px-2 py-0.5 lg:px-2.5 lg:py-1 rounded-md font-bold"><i class="fa-solid fa-location-dot fa-bounce"></i> กำลังไปรับ</span>
                                        @elseif($order->status == 'processing')
                                            <span class="inline-block bg-purple-100 text-purple-700 text-[9px] lg:text-[10px] px-2 py-0.5 lg:px-2.5 lg:py-1 rounded-md font-bold"><i class="fa-solid fa-jug-detergent"></i> กำลังซัก/อบ</span>
                                        @elseif($order->status == 'washing_completed')
                                            <span class="inline-block bg-indigo-100 text-indigo-700 text-[9px] lg:text-[10px] px-2 py-0.5 lg:px-2.5 lg:py-1 rounded-md font-bold"><i class="fa-solid fa-basket-shopping"></i> ซักเสร็จรอส่ง</span>
                                        @elseif($order->status == 'delivering')
                                            <span class="inline-block bg-sky-100 text-sky-700 text-[9px] lg:text-[10px] px-2 py-0.5 lg:px-2.5 lg:py-1 rounded-md font-bold"><i class="fa-solid fa-truck-fast fa-beat"></i> กำลังไปส่ง</span>
                                        @endif
                                    </td>

                                    {{-- 2. ลูกค้าและสถานที่ --}}
                                    <td class="px-3 py-3 lg:px-5 lg:py-4 align-top whitespace-normal">
                                        <p class="font-bold text-gray-800 dark:text-gray-100 text-sm lg:text-base">{{ $order->user->fullname ?? 'ลูกค้า' }}</p>
                                        @if($order->user->phone)
                                            <p class="text-[10px] lg:text-xs text-gray-600 dark:text-gray-400 mt-1"><i class="fa-solid fa-phone text-emerald-500 mr-1"></i>{{ $order->user->phone }}</p>
                                        @endif
                                        <p class="text-[10px] lg:text-xs text-gray-500 dark:text-gray-400 mt-1.5 leading-relaxed"><i class="fa-solid fa-location-crosshairs text-rose-500 mr-1"></i>{{ $order->pickup_address ?? $order->user->address }}</p>

                                        @php
                                            // สร้าง Google Maps Direction Link (เหมือนที่ Admin ใช้)
                                            $shopLat = config('washly.shop.latitude', env('SHOP_LATITUDE'));
                                            $shopLng = config('washly.shop.longitude', env('SHOP_LONGITUDE'));
                                            
                                            $destinationLat = $order->pickup_latitude ?? $order->user->latitude;
                                            $destinationLng = $order->pickup_longitude ?? $order->user->longitude;
                                            
                                            $mapLink = null;
                                            
                                            // ถ้ามีพิกัดทั้งต้นทางและปลายทาง ใช้ Direction API
                                            if ($shopLat && $shopLng && $destinationLat && $destinationLng) {
                                                $mapLink = "https://www.google.com/maps/dir/?api=1&origin={$shopLat},{$shopLng}&destination={$destinationLat},{$destinationLng}&travelmode=driving";
                                            } elseif ($destinationLat && $destinationLng) {
                                                // ถ้ามีแค่ปลายทาง ให้เปิดแผนที่ไปที่พิกัดนั้น
                                                $mapLink = "https://maps.google.com/?q={$destinationLat},{$destinationLng}";
                                            } else {
                                                // ถ้าไม่มีพิกัดเลย ใช้ที่อยู่แทน
                                                $address = $order->pickup_address ?? $order->user->address;
                                                if ($address) {
                                                    $mapLink = "https://maps.google.com/?q=" . urlencode($address);
                                                }
                                            }
                                        @endphp

                                        @if($mapLink)
                                            <a href="{{ $mapLink }}" target="_blank" class="mt-2 inline-flex items-center gap-1 text-[10px] lg:text-[11px] font-bold text-blue-600 hover:text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2 py-1 lg:px-2.5 lg:py-1.5 rounded-md border border-blue-100 dark:border-blue-800/50 transition">
                                                <i class="fa-solid fa-map-location-dot"></i> แผนที่นำทาง
                                            </a>
                                        @else
                                            <span class="mt-2 inline-flex items-center gap-1 text-[10px] lg:text-[11px] text-gray-400 bg-gray-100 dark:bg-slate-700 px-2 py-1 lg:px-2.5 lg:py-1.5 rounded-md font-bold">
                                                <i class="fa-solid fa-map"></i> ไม่มีที่อยู่
                                            </span>
                                        @endif
                                    </td>

                                    {{-- 3. การชำระเงิน --}}
                                    <td class="px-3 py-3 lg:px-5 lg:py-4 align-top">
                                        <p class="text-base lg:text-lg font-black text-sky-600 dark:text-sky-400 mb-0.5">฿{{ number_format($order->total_price) }}</p>
                                        <p class="text-[10px] lg:text-xs font-semibold {{ ($order->payment_method ?? 'transfer') === 'cash' ? 'text-amber-600 dark:text-amber-400' : 'text-blue-600 dark:text-blue-400' }} mb-2">
                                            {{ ($order->payment_method ?? 'transfer') === 'cash' ? 'เงินสดปลายทาง' : 'โอน/สแกน QR' }}
                                        </p>

                                        <div class="flex flex-col gap-1.5 lg:gap-2 items-start">
                                            @if ($order->payment_status == 'paid')
                                                <span class="text-[9px] lg:text-[11px] bg-emerald-100 text-emerald-700 px-2 py-1 lg:px-2.5 lg:py-1.5 rounded-md font-bold"><i class="fa-solid fa-circle-check"></i> ชำระแล้ว</span>
                                                @if (($order->payment_method ?? 'transfer') === 'transfer' && $order->payment_slip)
                                                    <button type="button" onclick="showSlip('{{ asset('storage/' . $order->payment_slip) }}')" class="text-[9px] lg:text-[11px] text-gray-600 bg-gray-100 hover:bg-gray-200 px-2 py-1 lg:px-2.5 lg:py-1.5 rounded-md font-bold transition"><i class="fa-solid fa-receipt"></i> ดูสลิป</button>
                                                @endif
                                            @elseif($order->payment_status == 'pending_cash')
                                                <span class="text-[9px] lg:text-[11px] bg-amber-100 text-amber-700 px-2 py-1 lg:px-2.5 lg:py-1.5 rounded-md font-bold w-full text-center"><i class="fa-solid fa-hand-holding-dollar"></i> รอเก็บเงินสด</span>
                                                <form action="{{ route('driver.orders.confirm_cash', $order->id) }}" method="POST" class="m-0 w-full" onsubmit="this.querySelector('button').disabled = true;">
                                                    @csrf
                                                    <button type="submit" class="w-full text-[9px] lg:text-[11px] bg-emerald-500 text-white px-2 py-1.5 rounded-md hover:bg-emerald-600 transition font-bold shadow-sm"><i class="fa-solid fa-check"></i> รับเงิน</button>
                                                </form>
                                            @elseif($order->payment_status == 'reviewing' && $order->payment_slip)
                                                <button type="button" onclick="showSlip('{{ asset('storage/' . $order->payment_slip) }}')" class="w-full text-center text-[9px] lg:text-[11px] bg-blue-100 text-blue-700 px-2 py-1.5 rounded-md hover:bg-blue-200 transition font-bold"><i class="fa-solid fa-image"></i> ตรวจสลิป</button>
                                                <div class="flex gap-1 lg:gap-1.5 w-full">
                                                    <form action="{{ route('driver.orders.approve_payment', $order->id) }}" method="POST" class="m-0 flex-1" onsubmit="this.querySelector('button').disabled = true;">
                                                        @csrf
                                                        <button type="submit" class="w-full text-[9px] lg:text-[11px] bg-emerald-500 text-white px-1 lg:px-2 py-1.5 rounded-md hover:bg-emerald-600 transition font-bold"><i class="fa-solid fa-check"></i> ผ่าน</button>
                                                    </form>
                                                    <form action="{{ route('driver.orders.reject_slip', $order->id) }}" method="POST" class="m-0 flex-1">
                                                        @csrf
                                                        <button type="button" onclick="confirmRejectSlip(this.closest('form'))" class="w-full text-[9px] lg:text-[11px] bg-rose-500 text-white px-1 lg:px-2 py-1.5 rounded-md hover:bg-rose-600 transition font-bold"><i class="fa-solid fa-xmark"></i> ปฏิเสธ</button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-[9px] lg:text-[11px] bg-gray-100 text-gray-500 px-2 py-1 lg:px-2.5 lg:py-1.5 rounded-md font-bold"><i class="fa-solid fa-hourglass-half"></i> รอชำระเงิน</span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- 4. อัปเดตสถานะ --}}
                                    <td class="px-3 py-3 lg:px-5 lg:py-4 align-top text-center">
                                        @php
                                            $driverStatuses = [
                                                'pending_pickup' => 'รอรับผ้า',
                                                'picking_up' => 'กำลังไปรับ',
                                                'processing' => 'กำลังซัก/อบ',
                                                'washing_completed' => 'ซักเสร็จ/รอส่ง',
                                                'delivering' => 'กำลังไปส่ง',
                                                'completed' => 'เสร็จสิ้น',
                                            ];
                                        @endphp
                                        <form action="{{ route('driver.orders.status', $order->id) }}" method="POST" class="m-0 flex flex-col items-center gap-1.5 lg:gap-2.5 mx-auto max-w-[150px]" onsubmit="this.querySelector('button[type=submit]').disabled = true; this.querySelector('button[type=submit]').innerHTML = 'บันทึก...';">
                                            @csrf @method('PUT')
                                            <select name="status" class="w-full text-[10px] lg:text-xs border border-sky-200 dark:border-slate-600 bg-white dark:bg-slate-700 font-bold text-gray-700 dark:text-gray-200 rounded-md lg:rounded-lg py-1.5 lg:py-2 px-1 lg:px-2 outline-none focus:border-sky-500 text-center shadow-sm cursor-pointer">
                                                @foreach ($driverStatuses as $key => $label)
                                                    <option value="{{ $key }}" {{ $order->status == $key ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="w-full px-2 py-1.5 lg:px-3 lg:py-2 bg-sky-500 hover:bg-sky-600 rounded-md lg:rounded-lg text-[10px] lg:text-xs font-bold text-white shadow-sm transition-all"><i class="fa-solid fa-floppy-disk mr-1"></i> บันทึกสถานะ</button>
                                        </form>
                                    </td>

                                    {{-- 5. ยกเลิกงาน --}}
                                    <td class="px-3 py-3 lg:px-5 lg:py-4 align-top text-center">
                                        @if(!in_array($order->status, ['completed', 'cancelled']))
                                            <div class="mx-auto max-w-[120px]">
                                                <button type="button" onclick="confirmDriverCancelOrder({{ $order->id }}, '{{ $order->order_number }}')" class="w-full text-[9px] lg:text-[11px] text-rose-500 hover:text-white bg-rose-50 dark:bg-rose-900/20 hover:bg-rose-500 py-1.5 lg:py-2 rounded-md lg:rounded-lg transition-colors font-bold border border-rose-200 dark:border-rose-800/50">
                                                    <i class="fa-solid fa-ban"></i> ขอยกเลิก
                                                </button>
                                                <form id="driver-cancel-form-{{ $order->id }}" action="{{ route('driver.orders.cancel', $order->id) }}" method="POST" class="hidden">
                                                    @csrf
                                                    <input type="hidden" name="cancel_reason" id="driver-cancel-reason-{{ $order->id }}">
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-[10px] lg:text-xs text-gray-400">-</span>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-10 lg:p-16 text-center flex flex-col items-center justify-center">
                    <div class="w-16 h-16 lg:w-24 lg:h-24 bg-gray-50 dark:bg-slate-700/50 rounded-full flex items-center justify-center shadow-inner mb-4">
                        <i class="fa-solid fa-mug-hot text-3xl lg:text-5xl text-gray-300 dark:text-gray-500"></i>
                    </div>
                    <h3 class="text-base lg:text-xl font-bold text-gray-700 dark:text-gray-300 mb-1">ยังไม่มีงานในมือ</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-xs lg:text-base">รอแอดมินจ่ายงานมาให้จ้า ☕</p>
                </div>
            @endif
        </div>
    </div>

</div>

{{-- เพิ่มสไตล์ Scrollbar ให้เลื่อนซ้ายขวาสะดวกบนคอมพิวเตอร์ --}}
<style>
    .custom-scrollbar::-webkit-scrollbar {
        height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9; 
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1; 
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8; 
    }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showSlip(imageUrl) {
        const isDark = document.documentElement.classList.contains('dark');
        Swal.fire({
            title: 'หลักฐานการโอนเงิน',
            imageUrl: imageUrl,
            imageAlt: 'สลิปโอนเงิน',
            confirmButtonText: 'ปิดหน้าต่าง',
            confirmButtonColor: '#0ea5e9',
            background: isDark ? '#1e293b' : '#ffffff',
            color: isDark ? '#f8fafc' : '#1e293b',
            customClass: { popup: 'rounded-3xl', image: 'rounded-xl max-h-[60vh] object-contain border border-slate-200 dark:border-slate-700' }
        });
    }

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

    function confirmDriverCancelOrder(orderId, orderNumber) {
        const isDark = document.documentElement.classList.contains('dark');
        Swal.fire({
            title: 'ยกเลิกออเดอร์ ' + orderNumber + ' ?',
            text: "ระบุเหตุผลที่ขอยกเลิกงานนี้ (เช่น รถเสีย, ติดต่อลูกค้าไม่ได้) แอดมินและลูกค้าจะเห็นเหตุผลนี้ครับ",
            icon: 'warning',
            input: 'textarea',
            inputPlaceholder: 'พิมพ์เหตุผลที่ยกเลิก...',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'ยืนยันยกเลิกงาน',
            cancelButtonText: 'ปิดหน้าต่าง',
            background: isDark ? '#1e293b' : '#ffffff',
            color: isDark ? '#f8fafc' : '#1e293b',
            customClass: { popup: 'rounded-3xl' },
            inputValidator: (value) => {
                if (!value.trim()) return 'กรุณาระบุเหตุผลการยกเลิกด้วยครับ!'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('driver-cancel-reason-' + orderId).value = result.value;
                document.getElementById('driver-cancel-form-' + orderId).submit();
            }
        });
    }
</script>
@endpush