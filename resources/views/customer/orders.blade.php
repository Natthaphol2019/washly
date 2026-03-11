@extends('layouts.customer')

@section('title', 'ออเดอร์ของฉัน - Washly')

@section('content')
    <div class="max-w-3xl mx-auto pb-12 px-4 sm:px-6">

        <div class="text-center mb-10 pt-8">
            <div class="inline-flex items-center justify-center bg-purple-100 dark:bg-slate-800 text-purple-500 dark:text-purple-400 w-20 h-20 rounded-full mb-5 shadow-sm hover:scale-110 transition-transform duration-300">
                <i class="fa-solid fa-clipboard-list text-4xl fa-bounce" style="--fa-animation-duration: 2.5s; --fa-bounce-jump-scale-x: 1; --fa-bounce-jump-scale-y: 1;"></i>
            </div>
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800 dark:text-gray-100 mb-3 tracking-tight">ออเดอร์ของฉัน</h2>
            <p class="text-gray-500 dark:text-gray-400 text-lg">ติดตามสถานะการซักผ้าของคุณได้ที่นี่ <i class="fa-solid fa-sparkles text-yellow-400 ml-1"></i></p>
        </div>

        <div id="orders-live-region">
            @if ($orders->isEmpty())
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-10 text-center shadow-sm border border-gray-100 dark:border-slate-700 transition-colors mt-6">
                    <div class="animate-bounce" style="animation-duration: 3s;">
                        <i class="fa-solid fa-basket-shopping text-7xl text-gray-200 dark:text-gray-700 mb-6 drop-shadow-sm"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-700 dark:text-gray-200 mb-2">ยังไม่มีประวัติการสั่งซักผ้า</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-8">ตะกร้าผ้าล้นแล้วหรือยัง? ให้เราจัดการให้สิ!</p>
                    <a href="{{ route('customer.book') }}" class="inline-flex items-center justify-center bg-gradient-to-r from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 text-white font-bold py-3.5 px-8 rounded-full shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <i class="fa-solid fa-plus mr-2 text-xl"></i> จองคิวเลย
                    </a>
                </div>
            @else
                <div class="space-y-6">
                    @foreach ($orders as $order)
                    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-md border border-gray-100 dark:border-slate-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">

                        <div class="absolute top-0 left-0 w-2.5 h-full transition-colors duration-300
                            @if ($order->status == 'pending') bg-yellow-400 
                            @elseif($order->status == 'picked_up') bg-blue-400 
                            @elseif($order->status == 'processing') bg-purple-400
                            @elseif($order->status == 'delivering') bg-indigo-400
                            @elseif($order->status == 'completed') bg-green-400
                            @else bg-red-400 @endif">
                        </div>

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
                                    @if ($order->status == 'pending')
                                        <span class="bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800 px-4 py-2 rounded-full text-sm font-bold flex items-center gap-2 w-max shadow-sm">
                                            <i class="fa-solid fa-clock-rotate-left fa-spin-pulse"></i> รอไรเดอร์เข้ารับผ้า
                                        </span>
                                    @elseif($order->status == 'picked_up')
                                        <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800 px-4 py-2 rounded-full text-sm font-bold flex items-center gap-2 w-max shadow-sm">
                                            <i class="fa-solid fa-motorcycle fa-bounce" style="--fa-animation-duration: 2s;"></i> ไรเดอร์รับผ้าแล้ว
                                        </span>
                                    @elseif($order->status == 'processing')
                                        <span class="bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 border border-purple-200 dark:border-purple-800 px-4 py-2 rounded-full text-sm font-bold flex items-center gap-2 w-max shadow-sm">
                                            <i class="fa-solid fa-jug-detergent fa-beat-fade"></i> กำลังซักและอบ
                                        </span>
                                    @elseif($order->status == 'delivering')
                                        <span class="bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800 px-4 py-2 rounded-full text-sm font-bold flex items-center gap-2 w-max shadow-sm">
                                            <i class="fa-solid fa-truck-fast fa-beat" style="--fa-animation-duration: 1.5s;"></i> กำลังนำส่งคืน
                                        </span>
                                    @elseif($order->status == 'completed')
                                        <span class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800 px-4 py-2 rounded-full text-sm font-bold flex items-center gap-2 w-max shadow-sm">
                                            <i class="fa-solid fa-check-double fa-flip" style="--fa-animation-duration: 3s;"></i> เสร็จสิ้นภารกิจ
                                        </span>
                                    @else
                                        <span class="bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800 px-4 py-2 rounded-full text-sm font-bold flex items-center gap-2 w-max shadow-sm">
                                            <i class="fa-solid fa-xmark fa-shake"></i> ยกเลิกออเดอร์
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex justify-between items-center mb-5">
                                <div>
                                    <p class="font-bold text-gray-800 dark:text-gray-200 text-lg">
                                        {{ $order->package->name ?? 'ไม่พบข้อมูลแพ็กเกจ' }}
                                    </p>
                                    <div class="flex items-center gap-4 mt-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">
                                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-droplet text-blue-500"></i> ซัก: {{ $order->wash_temp }}</span>
                                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-wind text-orange-500"></i> อบ: {{ $order->dry_temp }}</span>
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
                                            @if(($order->payment_method ?? 'transfer') === 'transfer')
                                                <a href="{{ route('customer.orders.pay', $order->id) }}" class="inline-block bg-pink-500 hover:bg-pink-600 text-white text-[11px] font-bold py-1.5 px-3 rounded-md shadow-sm hover:shadow transition-all">
                                                    ชำระเงิน
                                                </a>
                                            @else
                                                <span class="text-[10px] bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 px-2.5 py-1 rounded-md border border-gray-200 dark:border-slate-600 font-bold">
                                                    รอพนักงานเก็บเงิน
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($order->distance !== null || $order->straight_line_distance_km !== null || $order->pickup_map_link)
                                <div class="mb-5 rounded-xl bg-blue-50/80 dark:bg-slate-900/40 border border-blue-100 dark:border-slate-700 p-4">
                                    <p class="text-xs font-bold text-blue-600 dark:text-blue-300 mb-2.5 uppercase tracking-wider">ข้อมูลการเดินทาง</p>
                                    <div class="space-y-1.5 text-sm text-gray-700 dark:text-gray-300">
                                        @if($order->straight_line_distance_km !== null)
                                            <p><i class="fa-solid fa-location-crosshairs text-blue-500 mr-2"></i>ระยะเส้นตรงจากร้าน {{ number_format($order->straight_line_distance_km, 2) }} กม.</p>
                                        @endif
                                        @if($order->distance !== null)
                                            <p><i class="fa-solid fa-motorcycle text-pink-500 mr-2"></i>ระยะทางขับรถ {{ number_format($order->distance, 2) }} กม.</p>
                                        @endif
                                        @if($order->pickup_map_link)
                                            <a href="{{ $order->pickup_map_link }}" target="_blank" class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-300 font-semibold hover:underline">
                                                <i class="fa-solid fa-map-location-dot"></i> เปิดพิกัดจุดรับผ้า
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

                            @if ($order->logs->count() > 0)
                                <div class="mt-2 pt-5 border-t border-gray-100 dark:border-slate-700">
                                    <p class="text-sm font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
                                        <i class="fa-solid fa-list-check text-pink-500"></i> ประวัติสถานะ
                                    </p>
                                    <div class="space-y-4 pl-1">
                                        @foreach ($order->logs->take(3) as $index => $log)
                                            @php
                                                // กำหนด Icon และ สีตามสถานะ (ใช้ PHP ช่วยให้ HTML สะอาดขึ้น)
                                                $logIcon = 'fa-circle-dot';
                                                $logColor = 'text-gray-400';
                                                $logBg = 'bg-gray-100 dark:bg-slate-700';
                                                $logAnim = '';
                                                $logText = 'มีการอัปเดตสถานะ';

                                                switch($log->new_status) {
                                                    case 'picked_up':
                                                        $logIcon = 'fa-motorcycle'; $logColor = 'text-blue-500'; $logBg = 'bg-blue-100 dark:bg-blue-900/40'; $logAnim = 'fa-bounce'; $logText = 'ไรเดอร์รับผ้ามาแล้ว'; break;
                                                    case 'processing':
                                                        $logIcon = 'fa-jug-detergent'; $logColor = 'text-purple-500'; $logBg = 'bg-purple-100 dark:bg-purple-900/40'; $logAnim = 'fa-beat-fade'; $logText = 'เริ่มทำการซักและอบ'; break;
                                                    case 'delivering':
                                                        $logIcon = 'fa-truck-fast'; $logColor = 'text-indigo-500'; $logBg = 'bg-indigo-100 dark:bg-indigo-900/40'; $logAnim = 'fa-beat'; $logText = 'กำลังเดินทางไปส่งคืน'; break;
                                                    case 'completed':
                                                        $logIcon = 'fa-box-open'; $logColor = 'text-green-500'; $logBg = 'bg-green-100 dark:bg-green-900/40'; $logAnim = 'fa-bounce'; $logText = 'ส่งคืนสำเร็จเรียบร้อย!'; break;
                                                    case 'cancelled':
                                                        $logIcon = 'fa-xmark'; $logColor = 'text-red-500'; $logBg = 'bg-red-100 dark:bg-red-900/40'; $logAnim = 'fa-shake'; $logText = 'ออเดอร์ถูกยกเลิก'; break;
                                                }
                                                
                                                // ให้ขยับเฉพาะอันล่าสุด (อันแรกสุด) เพื่อไม่ให้ลายตาเกินไป
                                                $finalAnim = ($index === 0) ? $logAnim : '';
                                            @endphp

                                            <div class="flex items-start gap-4 relative">
                                                @if (!$loop->last)
                                                    <div class="absolute left-4 top-8 bottom-[-16px] w-[2px] bg-gray-100 dark:bg-slate-700 -translate-x-1/2"></div>
                                                @endif

                                                <div class="relative z-10 flex items-center justify-center w-8 h-8 rounded-full shrink-0 shadow-sm border border-white dark:border-slate-800 {{ $logBg }}">
                                                    <i class="fa-solid {{ $logIcon }} {{ $logColor }} text-sm {{ $finalAnim }}" style="--fa-animation-duration: 2s;"></i>
                                                </div>
                                                
                                                <div class="pt-1.5 pb-1">
                                                    <p class="text-sm font-bold {{ $index === 0 ? 'text-gray-800 dark:text-gray-100' : 'text-gray-600 dark:text-gray-400' }}">
                                                        {{ $logText }}
                                                    </p>
                                                    <p class="text-[11px] font-medium text-gray-400 dark:text-gray-500 mt-0.5 flex items-center gap-1">
                                                        <i class="fa-regular fa-clock"></i> {{ \Carbon\Carbon::parse($log->created_at)->format('d M H:i น.') }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    (function () {
        let refreshTimer = null;
        let isRefreshing = false;

        async function refreshOrdersSection() {
            if (isRefreshing) {
                return;
            }

            isRefreshing = true;

            try {
                const response = await fetch(window.location.href, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html',
                    },
                });

                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const nextRegion = doc.querySelector('#orders-live-region');
                const currentRegion = document.querySelector('#orders-live-region');

                if (nextRegion && currentRegion) {
                    currentRegion.innerHTML = nextRegion.innerHTML;
                } else {
                    window.location.reload();
                }
            } catch (error) {
                console.error('Refresh orders section failed', error);
                window.location.reload();
            } finally {
                isRefreshing = false;
            }
        }

        function scheduleRefresh() {
            if (refreshTimer !== null) {
                return;
            }

            refreshTimer = window.setTimeout(function () {
                refreshTimer = null;
                refreshOrdersSection();
            }, 500);
        }

        window.addEventListener('washly:notification-received', function (event) {
            const payload = event.detail || {};
            const haystack = `${payload.title || ''} ${payload.message || ''} ${payload.url || ''}`.toLowerCase();

            const isOrderRelated =
                haystack.includes('ออเดอร์') ||
                haystack.includes('สถานะ') ||
                haystack.includes('ชำระ') ||
                haystack.includes('/customer/orders');

            if (isOrderRelated) {
                scheduleRefresh();
            }
        });
    })();
</script>
@endpush