@extends('layouts.admin')

@section('title', 'Dashboard - Washly Admin')
@section('page_title', 'รายงานสรุปภาพรวม') {{-- ส่งชื่อไปโชว์ที่ Header --}}

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="max-w-7xl mx-auto w-full flex flex-col gap-6 pb-10">

        {{-- 🔹 หัวข้อหน้า --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl washly-card shadow-sm flex items-center justify-center border border-sky-100 dark:border-slate-700">
                    <i class="fa-solid fa-chart-line text-2xl text-sky-500"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold washly-shell">สรุปภาพรวมแพลตฟอร์ม</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">ติดตามกระแสเงินสด สถิติการใช้งาน และสถานะการขนส่ง</p>
                </div>
            </div>
            
            <button onclick="window.location.reload()" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-rotate-right"></i> รีเฟรชข้อมูล
            </button>
        </div>

        {{-- 🔹 4 กล่องสถิติด้านบน --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- กล่อง 1: ยอดเงินหมุนเวียน --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 flex items-center gap-4 transition-colors">
                <div class="w-14 h-14 rounded-full bg-emerald-50 dark:bg-emerald-900/40 text-emerald-500 flex items-center justify-center text-2xl shrink-0">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mb-1">ยอดเงินหมุนเวียน</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100">฿{{ number_format($orders->where('payment_status', 'paid')->sum('total_price')) }}</h3>
                </div>
            </div>

            {{-- กล่อง 2: งานด่วน รอรับผ้า --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 flex items-center gap-4 transition-colors">
                <div class="w-14 h-14 rounded-full bg-rose-50 dark:bg-rose-900/40 text-rose-500 flex items-center justify-center text-2xl shrink-0">
                    <i class="fa-solid fa-motorcycle"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mb-1">รอไรเดอร์รับผ้า</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $orders->where('status', 'pending_pickup')->count() }} <span class="text-sm font-normal text-gray-500">งาน</span></h3>
                </div>
            </div>

            {{-- กล่อง 3: ผ้ากำลังซัก --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 flex items-center gap-4 transition-colors">
                <div class="w-14 h-14 rounded-full bg-sky-50 dark:bg-sky-900/40 text-sky-500 flex items-center justify-center text-2xl shrink-0">
                    <i class="fa-solid fa-store"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mb-1">ผ้าอยู่ระหว่างซัก</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $orders->whereIn('status', ['picking_up', 'processing'])->count() }} <span class="text-sm font-normal text-gray-500">ออเดอร์</span></h3>
                </div>
            </div>

            {{-- กล่อง 4: สลิปรอตรวจ --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 flex items-center gap-4 transition-colors">
                <div class="w-14 h-14 rounded-full bg-amber-50 dark:bg-amber-900/40 text-amber-500 flex items-center justify-center text-2xl shrink-0">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mb-1">สลิปรอตรวจสอบ</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $orders->where('payment_status', 'reviewing')->count() }} <span class="text-sm font-normal text-gray-500">รายการ</span></h3>
                </div>
            </div>
        </div>

        {{-- 🔹 โซนกราฟ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- กราฟยอดขาย --}}
            <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-6 flex items-center gap-2"><i class="fa-solid fa-chart-area text-sky-500"></i> สถิติยอดออเดอร์และรายได้</h2>
                <div class="relative h-72 w-full">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            {{-- กราฟโดนัท สถานะ --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-6 flex items-center gap-2"><i class="fa-solid fa-chart-pie text-sky-500"></i> สัดส่วนสถานะงานวันนี้</h2>
                <div class="relative h-72 w-full flex justify-center">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        {{-- 🔹 ออเดอร์ล่าสุด --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden mt-2">
            <div class="p-5 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/50 flex justify-between items-center">
                <h2 class="text-lg font-bold washly-shell flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-sky-500"></i> ออเดอร์ล่าสุด
                </h2>
                <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-sky-600 hover:text-sky-700 dark:text-sky-400 hover:underline">ดูทั้งหมด <i class="fa-solid fa-arrow-right ml-1"></i></a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-600 dark:text-slate-300 border-b border-slate-200 dark:border-slate-700">
                        <tr>
                            <th class="px-6 py-4 font-semibold">รหัสออเดอร์</th>
                            <th class="px-6 py-4 font-semibold w-full">ลูกค้า</th>
                            <th class="px-6 py-4 font-semibold text-center">ยอดเงิน</th>
                            <th class="px-6 py-4 font-semibold text-center">สถานะขนส่ง</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50 washly-shell">
                        @forelse($orders->take(5) as $order)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-sky-600 dark:text-sky-400">{{ $order->order_number }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::parse($order->created_at)->addYears(543)->format('d/m/Y H:i') }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold">{{ $order->user->fullname ?? 'ไม่ระบุ' }}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <p class="font-bold text-gray-800 dark:text-gray-200">฿{{ number_format($order->total_price) }}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusLabels = [
                                            'pending' => 'รออนุมัติ', 'pending_pickup' => 'รอไรเดอร์รับ', 'picking_up' => 'กำลังไปรับ',
                                            'processing' => 'กำลังซัก/อบ', 'washing_completed' => 'รอส่งคืน', 'delivering' => 'กำลังไปส่ง',
                                            'completed' => 'เสร็จสิ้น', 'cancelled' => 'ยกเลิก',
                                        ];
                                        $statusStyles = [
                                            'pending_pickup' => 'text-rose-600 bg-rose-50 border border-rose-200 dark:bg-rose-900/30',
                                            'processing' => 'text-sky-600 bg-sky-50 border border-sky-200 dark:bg-sky-900/30',
                                            'completed' => 'text-emerald-600 bg-emerald-50 border border-emerald-200 dark:bg-emerald-900/30',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusStyles[$order->status] ?? 'text-slate-600 bg-slate-100 border border-slate-200' }}">
                                        {{ $statusLabels[$order->status] ?? $order->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-10 text-center text-gray-400">ยังไม่มีออเดอร์ล่าสุด</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- สคริปต์กราฟ (ส่วนใหญ่ใช้ของเดิมที่ซีเขียนไว้เลยครับ) --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#94a3b8' : '#64748b';
            const gridColor = isDark ? '#334155' : '#f1f5f9';

            // 1. กราฟรายได้
            const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctxRevenue, {
                type: 'line',
                data: {
                    labels: ['จันทร์', 'อังคาร', 'พุธ', 'พฤหัสฯ', 'ศุกร์', 'เสาร์', 'อาทิตย์'],
                    datasets: [{
                        label: 'ยอดเงินหมุนเวียน (GMV)',
                        data: [1200, 1900, 1500, 2200, 1800, 2800, 2500],
                        borderColor: '#0ea5e9', // สี sky-500
                        backgroundColor: 'rgba(14, 165, 233, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
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

            // 2. กราฟโดนัท สถานะ
            const pendingCount = {{ $orders->whereIn('status', ['pending', 'pending_pickup'])->count() }};
            const processingCount = {{ $orders->whereIn('status', ['picking_up', 'processing', 'washing_completed'])->count() }};
            const deliveringCount = {{ $orders->where('status', 'delivering')->count() }};
            
            const ctxStatus = document.getElementById('statusChart').getContext('2d');
            new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: ['รอรับผ้า', 'กำลังซัก', 'กำลังส่งคืน'],
                    datasets: [{
                        data: [pendingCount, processingCount, deliveringCount],
                        backgroundColor: ['#f43f5e', '#0ea5e9', '#8b5cf6'], // rose, sky, purple
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: { position: 'bottom', labels: { color: textColor, padding: 20, usePointStyle: true } }
                    }
                }
            });
        });
    </script>
@endsection