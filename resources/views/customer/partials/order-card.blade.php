@php
    $isHistory = $isHistory ?? false;
    $statusMap = [
        'pending' => ['color' => 'bg-yellow-400', 'icon' => 'fa-clock-rotate-left fa-spin-pulse', 'text' => 'รอรับผ้า', 'badge' => 'bg-yellow-100 text-yellow-700 border-yellow-200'],
        'pending_pickup' => ['color' => 'bg-yellow-400', 'icon' => 'fa-clock-rotate-left fa-spin-pulse', 'text' => 'รอรับผ้า', 'badge' => 'bg-yellow-100 text-yellow-700 border-yellow-200'],
        'picking_up' => ['color' => 'bg-blue-400', 'icon' => 'fa-motorcycle fa-bounce', 'text' => 'กำลังไปรับผ้า', 'badge' => 'bg-blue-100 text-blue-700 border-blue-200'],
        'processing' => ['color' => 'bg-purple-400', 'icon' => 'fa-jug-detergent fa-beat-fade', 'text' => 'อยู่ระหว่างซัก อบ พับ', 'badge' => 'bg-purple-100 text-purple-700 border-purple-200'],
        'delivering' => ['color' => 'bg-indigo-400', 'icon' => 'fa-truck-fast fa-beat', 'text' => 'กำลังจัดส่ง', 'badge' => 'bg-indigo-100 text-indigo-700 border-indigo-200'],
        'completed' => ['color' => 'bg-green-400', 'icon' => 'fa-check-double', 'text' => 'จัดส่งเรียบร้อย', 'badge' => 'bg-green-100 text-green-700 border-green-200'],
        'cancelled' => ['color' => 'bg-red-400', 'icon' => 'fa-xmark', 'text' => 'ยกเลิกออเดอร์', 'badge' => 'bg-red-100 text-red-700 border-red-200'],
    ];
    $status = $statusMap[$order->status] ?? $statusMap['pending'];
@endphp

<div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-md border border-gray-100 dark:border-slate-700 hover:shadow-xl transition-all duration-300 relative overflow-hidden group {{ $isHistory ? 'bg-gray-50/50 dark:bg-slate-800/50 opacity-80 hover:opacity-100' : '' }}">
    <div class="absolute top-0 left-0 w-2.5 h-full transition-colors duration-300 {{ $status['color'] }}"></div>

    <div class="pl-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 border-b border-gray-100 dark:border-slate-700 pb-4">
            <div>
                <p class="text-xs font-medium text-gray-400 dark:text-gray-500 mb-1">
                    <i class="fa-regular fa-calendar-days mr-1"></i>
                    {{ \Carbon\Carbon::parse($order->created_at)->addYears(543)->format('d M Y H:i') }}
                </p>
                <h3 class="text-xl font-black text-gray-800 dark:text-gray-100 tracking-tight">
                    <i class="fa-solid fa-receipt text-pink-500 mr-1.5 opacity-80"></i>{{ $order->order_number }}
                </h3>
            </div>
            <div>
                <span class="{{ $status['badge'] }} px-4 py-2 rounded-full text-sm font-bold flex items-center gap-2 w-max shadow-sm dark:bg-slate-700 dark:border-slate-600">
                    <i class="fa-solid {{ $status['icon'] }}" style="--fa-animation-duration: 2s;"></i> {{ $status['text'] }}
                </span>
            </div>
        </div>

        <div class="flex justify-between items-center mb-5">
            <div>
                <p class="font-bold text-gray-800 dark:text-gray-200 text-lg">
                    {{ $order->package->name ?? 'ไม่พบข้อมูลแพ็กเกจ' }}
                </p>
                <div class="flex items-center gap-4 mt-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-droplet text-blue-500"></i> ซัก: {{ $order->wash_temp ?? 'ปกติ' }}</span>
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-wind text-orange-500"></i> อบ: {{ $order->dry_temp ?? 'ปกติ' }}</span>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 mb-0.5">ยอดชำระ</p>
                <p class="text-2xl font-black text-pink-600 dark:text-pink-400">
                    ฿{{ number_format($order->total_price) }}
                </p>

                <div class="mt-1.5 flex flex-col items-end gap-1.5">
                    @if(($order->payment_method ?? 'transfer') === 'cash')
                        <span class="text-[10px] bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300 px-2.5 py-1 rounded-md border border-amber-200 dark:border-amber-800 font-bold">
                            <i class="fa-solid fa-money-bill-wave mr-1"></i> เงินสดปลายทาง
                        </span>
                    @else
                        <span class="text-[10px] bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 px-2.5 py-1 rounded-md border border-blue-200 dark:border-blue-800 font-bold">
                            <i class="fa-solid fa-qrcode mr-1"></i> โอน/สแกน QR
                        </span>
                    @endif

                    @if ($order->payment_status == 'paid')
                        <span class="text-[10px] bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 px-2.5 py-1 rounded-md border border-green-200 dark:border-green-800 font-bold">
                            <i class="fa-solid fa-circle-check"></i> ชำระเงินแล้ว
                        </span>
                    @elseif($order->payment_status == 'pending_cash')
                        <span class="text-[10px] bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300 px-2.5 py-1 rounded-md border border-amber-200 dark:border-amber-800 font-bold">
                            <i class="fa-solid fa-hand-holding-dollar"></i> รอรับเงินสด
                        </span>
                    @elseif($order->payment_status == 'reviewing')
                        <span class="text-[10px] bg-yellow-50 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-400 px-2.5 py-1 rounded-md border border-yellow-200 dark:border-yellow-800 font-bold">
                            <i class="fa-solid fa-spinner fa-spin"></i> รอตรวจสอบสลิป
                        </span>
                    @else
                        @if(($order->payment_method ?? 'transfer') === 'transfer' && !$isHistory)
                            <div class="flex flex-col items-end gap-1.5">
                                <a href="{{ route('customer.orders.pay', $order->id) }}" class="inline-block bg-pink-500 hover:bg-pink-600 text-white text-[11px] font-bold py-1.5 px-3 rounded-md shadow-sm hover:shadow transition-all">
                                    ชำระเงิน
                                </a>
                                <button type="button" 
                                    onclick="showOrderQRCode({{ $order->id }}, {{ $order->total_price }}, '{{ $order->order_number }}')"
                                    class="inline-block bg-emerald-500 hover:bg-emerald-600 text-white text-[11px] font-bold py-1.5 px-3 rounded-md shadow-sm hover:shadow transition-all flex items-center gap-1">
                                    <i class="fa-solid fa-qrcode"></i> สแกน QR
                                </button>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        {{-- 🚚 3. เพิ่มข้อมูลพนักงานขับรถ (ถ้ามี) --}}
        @if($order->driver_id)
            <div class="mb-5 rounded-xl bg-sky-50/80 dark:bg-slate-900/40 border border-sky-100 dark:border-slate-700 p-4 flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-sky-200 dark:bg-sky-800 text-sky-600 dark:text-sky-300 flex items-center justify-center font-bold shrink-0">
                    <i class="fa-solid fa-motorcycle"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-sky-600 dark:text-sky-300 mb-0.5 uppercase tracking-wider">พนักงานรับ-ส่งผ้า</p>
                    <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $order->driver->fullname ?? 'ไม่ทราบชื่อ' }}</p>
                    @if($order->driver && $order->driver->phone)
                        <p class="text-xs mt-0.5 text-gray-500 font-medium"><i class="fa-solid fa-phone mr-1 text-[10px]"></i> {{ $order->driver->phone }}</p>
                    @endif
                </div>
            </div>
        @endif

        {{-- 📍 ข้อมูลการเดินทาง / พิกัด --}}
        @if($order->distance !== null || $order->straight_line_distance_km !== null || $order->pickup_map_link)
            <div class="mb-5 rounded-xl bg-blue-50/80 dark:bg-slate-900/40 border border-blue-100 dark:border-slate-700 p-4">
                <p class="text-xs font-bold text-blue-600 dark:text-blue-300 mb-2.5 uppercase tracking-wider">ข้อมูลพิกัด / การเดินทาง</p>
                <div class="space-y-1.5 text-sm text-gray-700 dark:text-gray-300">
                    @if($order->straight_line_distance_km !== null)
                        <p><i class="fa-solid fa-location-crosshairs text-blue-500 mr-2"></i>ระยะเส้นตรงจากร้าน {{ number_format($order->straight_line_distance_km, 2) }} กม.</p>
                    @endif
                    @if($order->distance !== null)
                        <p><i class="fa-solid fa-motorcycle text-pink-500 mr-2"></i>ระยะทางขับรถ {{ number_format($order->distance, 2) }} กม.</p>
                    @endif
                    @if($order->pickup_map_link)
                        <a href="{{ $order->pickup_map_link }}" target="_blank" class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-300 font-semibold hover:underline mt-1">
                            <i class="fa-solid fa-map-location-dot"></i> เปิดดูพิกัดจุดรับ/ส่งผ้า
                        </a>
                    @endif
                </div>
            </div>
        @endif

        @if (!empty($order->selected_addons))
            <div class="mb-5 rounded-xl bg-gray-50/80 dark:bg-slate-900/40 border border-gray-100 dark:border-slate-700 p-4">
                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 mb-2.5 uppercase tracking-wider">เมนูเสริมที่เลือก</p>
                <div class="space-y-2">
                    @foreach ($order->selected_addons as $addon)
                        <p class="text-sm text-gray-700 dark:text-gray-300 flex justify-between gap-3 items-center">
                            <span class="font-medium">
                                {{ $addon['name'] ?? '-' }} 
                                <span class="text-gray-400 mx-1">x</span> {{ $addon['qty'] ?? 1 }}
                                @if(!empty($addon['auto_selected']))
                                    <span class="ml-2 inline-flex items-center rounded-full bg-sky-100 text-sky-700 dark:bg-sky-900/50 dark:text-sky-300 px-2 py-0.5 text-[10px] font-bold tracking-wide">AUTO</span>
                                @endif
                            </span>
                            <span class="font-bold text-pink-500">฿{{ number_format($addon['line_total'] ?? 0) }}</span>
                        </p>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- แสดงเหตุผลการยกเลิก (ถ้ามี) --}}
        @if ($order->status == 'cancelled' && !empty($order->cancel_reason))
            <div class="mt-4 mb-2 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                <p class="text-xs font-bold text-red-600 dark:text-red-400 mb-1"><i class="fa-solid fa-circle-exclamation"></i> เหตุผลการยกเลิก:</p>
                <p class="text-sm text-red-700 dark:text-red-300">{{ $order->cancel_reason }}</p>
            </div>
        @endif

        @if ($order->logs->count() > 0 && !$isHistory)
            <div class="mt-2 pt-5 border-t border-gray-100 dark:border-slate-700">
                <p class="text-sm font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-pink-500"></i> ประวัติสถานะล่าสุด
                </p>
                <div class="space-y-4 pl-1">
                    @foreach ($order->logs->take(2) as $index => $log)
                        @php
                            $logIcon = 'fa-circle-dot'; $logColor = 'text-gray-400'; $logBg = 'bg-gray-100'; $logText = 'อัปเดตสถานะ';
                            switch($log->new_status) {
                                case 'picking_up': $logIcon = 'fa-motorcycle'; $logColor = 'text-blue-500'; $logBg = 'bg-blue-100'; $logText = 'กำลังไปรับผ้า'; break;
                                case 'processing': $logIcon = 'fa-jug-detergent'; $logColor = 'text-purple-500'; $logBg = 'bg-purple-100'; $logText = 'อยู่ระหว่างซัก อบ พับ'; break;
                                case 'delivering': $logIcon = 'fa-truck-fast'; $logColor = 'text-indigo-500'; $logBg = 'bg-indigo-100'; $logText = 'กำลังจัดส่ง'; break;
                                case 'completed': $logIcon = 'fa-check-double'; $logColor = 'text-green-500'; $logBg = 'bg-green-100'; $logText = 'จัดส่งเรียบร้อย'; break;
                                case 'washing_completed': $logIcon = 'fa-basket-shopping'; $logColor = 'text-purple-500'; $logBg = 'bg-purple-100'; $logText = 'อยู่ระหว่างซัก อบ พับ'; break; 
                            }
                        @endphp
                        <div class="flex items-start gap-4 relative">
                            @if (!$loop->last)
                                <div class="absolute left-4 top-8 bottom-[-16px] w-[2px] bg-gray-100 dark:bg-slate-700 -translate-x-1/2"></div>
                            @endif
                            <div class="relative z-10 flex items-center justify-center w-8 h-8 rounded-full shrink-0 shadow-sm border border-white dark:border-slate-800 {{ $logBg }}">
                                <i class="fa-solid {{ $logIcon }} {{ $logColor }} text-sm"></i>
                            </div>
                            <div class="pt-1.5 pb-1">
                                <p class="text-sm font-bold {{ $index === 0 ? 'text-gray-800 dark:text-gray-100' : 'text-gray-500' }}">{{ $logText }}</p>
                                <p class="text-[11px] font-medium text-gray-400 mt-0.5"><i class="fa-regular fa-clock"></i> {{ \Carbon\Carbon::parse($log->created_at)->format('d M H:i น.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>