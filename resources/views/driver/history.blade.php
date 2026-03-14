@extends('layouts.driver')
@section('title', 'ประวัติการทำงาน - ไรเดอร์')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:py-8 px-3 sm:px-6 space-y-8 sm:space-y-10 pb-20">
    
    {{-- 🌟 หัวข้อหลัก (Header Banner) สไตล์คลีน --}}
    <div class="relative overflow-hidden rounded-2xl sm:rounded-3xl bg-white dark:bg-slate-800 p-5 sm:p-8 shadow-sm border border-slate-200 dark:border-slate-700 transition-colors duration-500 flex justify-between items-center">
        <div class="relative z-10">
            <p class="text-gray-500 dark:text-gray-400 font-medium mb-1.5 flex items-center gap-2 text-xs sm:text-base transition-colors duration-500">
                <i class="fa-solid fa-clock-rotate-left text-emerald-500 dark:text-emerald-400"></i> งานที่ผ่านมาทั้งหมด
            </p>
            <h1 class="text-2xl sm:text-4xl font-black tracking-tight text-gray-800 dark:text-gray-100 transition-colors duration-500">
                ประวัติการทำงาน
            </h1>
        </div>
        <div class="hidden sm:flex relative z-10 w-20 h-20 bg-emerald-50 dark:bg-slate-700/50 rounded-full items-center justify-center border border-emerald-100 dark:border-slate-600 transition-colors duration-500">
            <i class="fa-solid fa-clipboard-check text-4xl text-emerald-500 dark:text-emerald-400"></i>
        </div>
        <div class="absolute -right-10 -top-10 w-32 sm:w-40 h-32 sm:h-40 bg-emerald-100 dark:bg-emerald-900/20 opacity-60 rounded-full blur-2xl"></div>
        <div class="absolute right-20 -bottom-10 w-24 sm:w-32 h-24 sm:h-32 bg-teal-100 dark:bg-teal-900/20 opacity-60 rounded-full blur-xl"></div>
    </div>

    {{-- ตารางประวัติ --}}
    <div class="bg-white dark:bg-slate-800 rounded-xl sm:rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        @if($orders->count() > 0)
            <div class="overflow-x-auto custom-scrollbar">
                {{-- 🌟 ปรับ min-w ให้เล็กลงในมือถือ (650px) และขยายในจอใหญ่ (900px) --}}
                <table class="w-full text-left whitespace-nowrap min-w-[650px] lg:min-w-[900px]">
                    <thead class="bg-slate-50 dark:bg-slate-800/80 text-slate-600 dark:text-slate-300 border-b border-slate-100 dark:border-slate-700">
                        <tr>
                            {{-- 🌟 ล็อคความกว้างของแต่ละคอลัมน์ --}}
                            <th class="px-3 py-3 lg:px-5 lg:py-4 font-bold text-xs lg:text-sm w-[25%] min-w-[140px] lg:min-w-[180px]">วันเวลา / ออเดอร์</th>
                            <th class="px-3 py-3 lg:px-5 lg:py-4 font-bold text-xs lg:text-sm w-[35%] min-w-[200px] lg:min-w-[280px]">ลูกค้า / สถานที่รับส่ง</th>
                            <th class="px-3 py-3 lg:px-5 lg:py-4 font-bold text-xs lg:text-sm w-[20%] min-w-[120px] lg:min-w-[160px]">การชำระเงิน</th>
                            <th class="px-3 py-3 lg:px-5 lg:py-4 font-bold text-center text-xs lg:text-sm w-[20%] min-w-[130px] lg:min-w-[160px]">สถานะงาน</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        @foreach($orders as $order)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                
                                {{-- 1. ออเดอร์และเวลา --}}
                                <td class="px-3 py-3 lg:px-5 lg:py-4 align-top">
                                    <p class="font-bold text-gray-800 dark:text-gray-200 text-sm lg:text-base mb-1.5">#{{ $order->order_number }}</p>
                                    <p class="text-[10px] lg:text-xs text-gray-500 dark:text-gray-400"><i class="fa-regular fa-calendar-check mr-1.5"></i>{{ \Carbon\Carbon::parse($order->updated_at)->addYears(543)->format('d M Y, H:i') }}</p>
                                </td>

                                {{-- 2. ลูกค้า --}}
                                <td class="px-3 py-3 lg:px-5 lg:py-4 align-top whitespace-normal">
                                    <p class="font-bold text-gray-800 dark:text-gray-100 text-sm lg:text-base">{{ $order->user->fullname ?? 'ไม่ระบุ' }}</p>
                                    <p class="text-[10px] lg:text-xs text-gray-500 dark:text-gray-400 mt-1.5 leading-relaxed flex items-start gap-1.5">
                                        <i class="fa-solid fa-location-dot text-gray-400 mt-0.5"></i>
                                        <span>{{ $order->pickup_address ?? $order->user->address }}</span>
                                    </p>
                                </td>

                                {{-- 3. การเงิน --}}
                                <td class="px-3 py-3 lg:px-5 lg:py-4 align-top">
                                    <p class="text-base lg:text-lg font-black text-gray-700 dark:text-gray-200 mb-0.5">฿{{ number_format($order->total_price) }}</p>
                                    <p class="text-[10px] lg:text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5">
                                        {{ ($order->payment_method ?? 'transfer') === 'cash' ? 'เงินสด' : 'โอน/สแกน' }}
                                    </p>
                                    @if ($order->payment_status == 'paid')
                                        <span class="inline-block text-[9px] lg:text-[10px] text-emerald-600 bg-emerald-50 dark:bg-emerald-900/30 px-2 py-1 lg:px-2.5 rounded font-bold border border-emerald-200 dark:border-emerald-800"><i class="fa-solid fa-circle-check"></i> ชำระแล้ว</span>
                                    @else
                                        <span class="inline-block text-[9px] lg:text-[10px] text-rose-500 bg-rose-50 dark:bg-rose-900/30 px-2 py-1 lg:px-2.5 rounded font-bold border border-rose-200 dark:border-rose-800"><i class="fa-solid fa-circle-xmark"></i> ยังไม่ชำระ</span>
                                    @endif
                                </td>

                                {{-- 4. สถานะ --}}
                                <td class="px-3 py-3 lg:px-5 lg:py-4 align-top text-center">
                                    @if($order->status == 'completed')
                                        <div class="inline-flex flex-col items-center justify-center bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/50 rounded-xl py-1.5 lg:py-2 px-3 lg:px-4 w-28 lg:w-32 mx-auto">
                                            <i class="fa-solid fa-check-double text-emerald-500 text-base lg:text-lg mb-1"></i>
                                            <span class="text-[10px] lg:text-xs font-bold text-emerald-700 dark:text-emerald-400">เสร็จสิ้น</span>
                                        </div>
                                    @elseif($order->status == 'cancelled')
                                        <div class="inline-flex flex-col items-center justify-center bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800/50 rounded-xl py-1.5 lg:py-2 px-3 lg:px-4 w-28 lg:w-32 mx-auto">
                                            <i class="fa-solid fa-ban text-rose-500 text-base lg:text-lg mb-1"></i>
                                            <span class="text-[10px] lg:text-xs font-bold text-rose-700 dark:text-rose-400">ถูกยกเลิก</span>
                                        </div>
                                        @if($order->cancel_reason)
                                            <p class="text-[9px] lg:text-[10px] text-rose-500 mt-2 max-w-[140px] lg:max-w-[160px] mx-auto whitespace-normal leading-tight text-center bg-rose-50 dark:bg-rose-900/20 p-1 lg:p-1.5 rounded">
                                                {{ Str::limit($order->cancel_reason, 60) }}
                                            </p>
                                        @endif
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- 🔗 Pagination (แบ่งหน้า) --}}
            @if ($orders->hasPages())
                <div class="p-3 lg:p-5 border-t border-slate-100 dark:border-slate-700 text-xs lg:text-sm">
                    {{ $orders->links() }}
                </div>
            @endif
        @else
            {{-- กรณีไม่มีประวัติเลย --}}
            <div class="p-10 lg:p-16 text-center flex flex-col items-center justify-center">
                <div class="w-16 h-16 lg:w-24 lg:h-24 bg-slate-50 dark:bg-slate-700/50 rounded-full flex items-center justify-center shadow-inner mb-4">
                    <i class="fa-solid fa-box-open text-3xl lg:text-5xl text-slate-300 dark:text-slate-500"></i>
                </div>
                <h3 class="text-base lg:text-xl font-bold text-gray-700 dark:text-gray-300 mb-1 lg:mb-2">ยังไม่มีประวัติการทำงาน</h3>
                <p class="text-gray-500 dark:text-gray-400 text-xs lg:text-sm">งานที่ทำสำเร็จหรือถูกยกเลิกจะมาแสดงผลที่หน้านี้ครับ</p>
            </div>
        @endif
    </div>

</div>

{{-- เพิ่มสไตล์ Scrollbar ให้เลื่อนซ้ายขวาสะดวกบนมือถือ/คอมพิวเตอร์ --}}
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