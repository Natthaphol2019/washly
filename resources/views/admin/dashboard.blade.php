@extends('layouts.admin')

@section('title', 'Dashboard - Washly Admin')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="max-w-7xl mx-auto w-full flex flex-col gap-6 pb-10">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                    <i class="fa-solid fa-chart-line text-indigo-500 mr-2"></i> สรุปภาพรวมแพลตฟอร์ม
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">ติดตามกระแสเงินสด สถิติการใช้งาน และสถานะการขนส่งแบบเรียลไทม์</p>
            </div>
            <button onclick="window.location.reload()" class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700 text-gray-600 dark:text-gray-300 px-4 py-2 rounded-xl text-sm font-medium transition-colors shadow-sm flex items-center gap-2 w-max">
                <i class="fa-solid fa-rotate-right"></i> รีเฟรชข้อมูล
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 flex items-center gap-4 transition-colors relative overflow-hidden">
                <div class="w-14 h-14 rounded-full bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400 flex items-center justify-center text-2xl shrink-0 z-10"><i class="fa-solid fa-sack-dollar"></i></div>
                <div class="z-10">
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mb-1">ยอดเงินหมุนเวียน (GMV)</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100">฿{{ number_format($orders->where('payment_status', 'paid')->sum('total_price')) }}</h3>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-red-100 dark:border-red-900/30 flex items-center gap-4 transition-colors relative overflow-hidden">
                <div class="w-14 h-14 rounded-full bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400 flex items-center justify-center text-2xl shrink-0 z-10"><i class="fa-solid fa-motorcycle"></i></div>
                <div class="z-10">
                    <p class="text-sm text-red-500 dark:text-red-400 font-medium mb-1">งานด่วน! รอรับผ้า</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $orders->where('status', 'pending')->count() }} <span class="text-sm font-normal text-gray-500">ออเดอร์</span></h3>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 flex items-center gap-4 transition-colors relative overflow-hidden">
                <div class="w-14 h-14 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-2xl shrink-0 z-10"><i class="fa-solid fa-store"></i></div>
                <div class="z-10">
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mb-1">ผ้าอยู่ระหว่างซัก</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $orders->whereIn('status', ['picked_up', 'processing'])->count() }} <span class="text-sm font-normal text-gray-500">ออเดอร์</span></h3>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-amber-100 dark:border-amber-900/30 flex items-center gap-4 transition-colors relative overflow-hidden">
                <div class="w-14 h-14 rounded-full bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 flex items-center justify-center text-2xl shrink-0 z-10"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                <div class="z-10">
                    <p class="text-sm text-amber-600 dark:text-amber-400 font-medium mb-1">สลิปรอตรวจสอบ</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $orders->where('payment_status', 'reviewing')->count() }} <span class="text-sm font-normal text-gray-500">รายการ</span></h3>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-5">
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4"><i class="fa-solid fa-chart-area text-indigo-500 mr-2"></i> สถิติยอดออเดอร์และรายได้</h2>
                <div class="relative h-64 w-full">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-5">
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4"><i class="fa-solid fa-chart-pie text-indigo-500 mr-2"></i> สัดส่วนสถานะงานวันนี้</h2>
                <div class="relative h-64 w-full flex justify-center">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-5">
                <h2 class="text-base font-bold text-gray-800 dark:text-gray-100 mb-3">รายได้ตามวิธีชำระเงิน</h2>
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-300">เงินสดปลายทาง (รับแล้ว)</span>
                        <span class="font-bold text-amber-600">฿{{ number_format($paidCashTotal ?? 0) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-300">โอน/สแกน QR (ชำระแล้ว)</span>
                        <span class="font-bold text-blue-600">฿{{ number_format($paidTransferTotal ?? 0) }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-5">
                <h2 class="text-base font-bold text-gray-800 dark:text-gray-100 mb-3">สัดส่วนการชำระเงิน</h2>
                <div class="relative h-48">
                    <canvas id="paymentChart"></canvas>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-5">
            <h2 class="text-base font-bold text-gray-800 dark:text-gray-100 mb-4">เมนูเสริมขายดี (ออเดอร์ที่ชำระแล้ว)</h2>
            @if(($topAddonStats ?? collect())->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="relative h-56">
                        <canvas id="addonChart"></canvas>
                    </div>
                    <div class="space-y-2">
                        @foreach($topAddonStats as $row)
                            <div class="rounded-xl border border-gray-100 dark:border-slate-700 p-3 flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-700 dark:text-gray-200">{{ $row['name'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $row['code'] }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-indigo-600">{{ number_format($row['qty']) }} หน่วย</p>
                                    <p class="text-xs text-gray-500">฿{{ number_format($row['revenue']) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <p class="text-sm text-gray-500">ยังไม่มีข้อมูลเมนูเสริมที่ชำระแล้ว</p>
            @endif
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden transition-colors">
            <div class="p-5 border-b border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/50 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100"><i class="fa-solid fa-clock-rotate-left text-indigo-500 mr-2"></i> ความเคลื่อนไหวล่าสุด (Recent Activity)</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 font-medium hover:underline">ดูออเดอร์ทั้งหมด <i class="fa-solid fa-arrow-right ml-1"></i></a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-slate-900/50 text-gray-500 dark:text-gray-400 text-sm uppercase tracking-wider border-b border-gray-200 dark:border-slate-700">
                            <th class="p-4 font-medium whitespace-nowrap">รหัสออเดอร์</th>
                            <th class="p-4 font-medium w-full">ลูกค้า</th>
                            <th class="p-4 font-medium whitespace-nowrap text-center">ยอดเงิน (การชำระ)</th>
                            <th class="p-4 font-medium text-center whitespace-nowrap w-px">สถานะขนส่ง</th>
                            <th class="p-4 font-medium text-center whitespace-nowrap w-px">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                        @forelse($orders->take(5) as $order) <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                                
                                <td class="p-4 whitespace-nowrap align-middle">
                                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $order->order_number }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ \Carbon\Carbon::parse($order->created_at)->addYears(543)->format('d/m/Y H:i') }}</p>
                                </td>

                                <td class="p-4 align-middle">
                                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $order->user->fullname ?? 'ไม่ระบุ' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"><i class="fa-solid fa-phone mr-1"></i> {{ $order->user->phone ?? '-' }}</p>
                                </td>

                                <td class="p-4 whitespace-nowrap align-middle text-center">
                                    <p class="text-sm font-bold text-gray-800 dark:text-gray-200">฿{{ number_format($order->total_price) }}</p>
                                    @if($order->payment_status == 'paid')
                                        <span class="text-[11px] text-green-600 dark:text-green-400 font-medium mt-1 inline-block bg-green-50 dark:bg-green-900/20 px-2 py-0.5 rounded-full"><i class="fa-solid fa-check"></i> ชำระแล้ว</span>
                                    @elseif($order->payment_status == 'reviewing')
                                        <span class="text-[11px] text-amber-600 dark:text-amber-400 font-medium mt-1 inline-block bg-amber-50 dark:bg-amber-900/20 px-2 py-0.5 rounded-full"><i class="fa-solid fa-clock"></i> รอตรวจสอบสลิป</span>
                                    @else
                                        <span class="text-[11px] text-gray-500 dark:text-gray-400 font-medium mt-1 inline-block bg-gray-100 dark:bg-slate-700 px-2 py-0.5 rounded-full">รอชำระเงิน</span>
                                    @endif
                                </td>

                                <td class="p-4 text-center whitespace-nowrap w-px align-middle">
                                    @php
                                        $statusStyles = [
                                            'pending' => 'text-red-600 bg-red-50 dark:bg-red-900/20',
                                            'picked_up' => 'text-yellow-600 bg-yellow-50 dark:bg-yellow-900/20',
                                            'processing' => 'text-blue-600 bg-blue-50 dark:bg-blue-900/20',
                                            'delivering' => 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20',
                                            'completed' => 'text-green-600 bg-green-50 dark:bg-green-900/20',
                                            'cancelled' => 'text-gray-500 bg-gray-100 dark:bg-slate-700',
                                        ];
                                        $statusLabels = [
                                            'pending' => 'รอไรเดอร์ไปรับ', 'picked_up' => 'รับผ้ามาแล้ว', 'processing' => 'ร้านกำลังซัก/อบ',
                                            'delivering' => 'ไรเดอร์กำลังส่งคืน', 'completed' => 'จัดส่งสำเร็จ', 'cancelled' => 'ยกเลิก',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1.5 rounded-full text-xs font-bold {{ $statusStyles[$order->status] ?? 'text-gray-600 bg-gray-100' }}">
                                        {{ $statusLabels[$order->status] ?? $order->status }}
                                    </span>
                                </td>

                                <td class="p-4 text-center whitespace-nowrap w-px align-middle">
                                    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 dark:bg-indigo-900/30 dark:text-indigo-400 dark:hover:bg-indigo-900/50 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                        จัดการ <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-10 text-center text-gray-500 dark:text-gray-400">
                                    <p class="text-lg font-medium">ยังไม่มีออเดอร์ล่าสุด</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // เช็กว่าใช้ Dark Mode อยู่ไหมเพื่อปรับสีกราฟให้เข้ากัน
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#94a3b8' : '#64748b';
            const gridColor = isDark ? '#334155' : '#f1f5f9';

            // 1. กราฟรายได้ (สมมติข้อมูลจำลอง 7 วันย้อนหลัง)
            // ของจริงซีสามารถส่งตัวแปร Array จาก Controller มาใส่ตรง data ได้เลยครับ
            const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctxRevenue, {
                type: 'line',
                data: {
                    labels: ['จันทร์', 'อังคาร', 'พุธ', 'พฤหัสฯ', 'ศุกร์', 'เสาร์', 'อาทิตย์'],
                    datasets: [{
                        label: 'ยอดเงินหมุนเวียน (GMV)',
                        data: [1200, 1900, 1500, 2200, 1800, 2800, 2500],
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4 // ทำให้เส้นโค้งสวยงาม
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { ticks: { color: textColor }, grid: { color: gridColor } },
                        x: { ticks: { color: textColor }, grid: { display: false } }
                    }
                }
            });

            // 2. กราฟโดนัท สัดส่วนสถานะออเดอร์
            const pendingCount = {{ $orders->where('status', 'pending')->count() }};
            const processingCount = {{ $orders->whereIn('status', ['picked_up', 'processing'])->count() }};
            const deliveringCount = {{ $orders->where('status', 'delivering')->count() }};
            
            const ctxStatus = document.getElementById('statusChart').getContext('2d');
            new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: ['รอไรเดอร์รับ', 'กำลังซัก/อบ', 'กำลังส่งคืน'],
                    datasets: [{
                        data: [pendingCount, processingCount, deliveringCount],
                        backgroundColor: ['#ef4444', '#3b82f6', '#8b5cf6'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: { position: 'bottom', labels: { color: textColor, padding: 20 } }
                    }
                }
            });

            const cashPaid = {{ (float) ($paidCashTotal ?? 0) }};
            const transferPaid = {{ (float) ($paidTransferTotal ?? 0) }};

            const ctxPayment = document.getElementById('paymentChart').getContext('2d');
            new Chart(ctxPayment, {
                type: 'doughnut',
                data: {
                    labels: ['เงินสดปลายทาง', 'โอน/สแกน QR'],
                    datasets: [{
                        data: [cashPaid, transferPaid],
                        backgroundColor: ['#f59e0b', '#3b82f6'],
                        borderWidth: 0,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: textColor, padding: 16 }
                        }
                    }
                }
            });

            const addonLabels = @json(($topAddonStats ?? collect())->pluck('name')->values());
            const addonQtyData = @json(($topAddonStats ?? collect())->pluck('qty')->values());

            const addonCanvas = document.getElementById('addonChart');
            if (addonCanvas && addonLabels.length > 0) {
                const ctxAddon = addonCanvas.getContext('2d');
                new Chart(ctxAddon, {
                    type: 'bar',
                    data: {
                        labels: addonLabels,
                        datasets: [{
                            label: 'จำนวนหน่วยที่ขาย',
                            data: addonQtyData,
                            backgroundColor: '#3b82f6',
                            borderRadius: 8,
                            maxBarThickness: 42,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { ticks: { color: textColor }, grid: { color: gridColor } },
                            x: { ticks: { color: textColor }, grid: { display: false } }
                        }
                    }
                });
            }
        });
    </script>
@endsection