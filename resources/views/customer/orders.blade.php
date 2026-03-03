@extends('layouts.customer')

@section('title', 'ออเดอร์ของฉัน - Washly')

@section('content')
    <div class="max-w-3xl mx-auto pb-10">

        <div class="text-center mb-8 pt-4">
            <div
                class="inline-block bg-purple-100 dark:bg-slate-700 text-purple-500 dark:text-purple-400 p-4 rounded-full mb-4 shadow-sm">
                <i class="fa-solid fa-clipboard-list text-3xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-2">ออเดอร์ของฉัน</h2>
            <p class="text-gray-500 dark:text-gray-400">ติดตามสถานะการซักผ้าของคุณได้ที่นี่ 👕✨</p>
        </div>

        @if ($orders->isEmpty())
            <div
                class="bg-white dark:bg-slate-800 rounded-[30px] p-10 text-center shadow-sm border border-pink-50 dark:border-slate-700 transition-colors mt-6">
                <i class="fa-solid fa-box-open text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                <h3 class="text-xl font-medium text-gray-700 dark:text-gray-300 mb-2">ยังไม่มีประวัติการสั่งซักผ้า</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">ตะกร้าผ้าล้นแล้วหรือยัง? ให้เราจัดการให้สิ!</p>
                <a href="{{ route('customer.book') }}"
                    class="inline-block bg-pink-500 hover:bg-pink-600 text-white font-medium py-3 px-8 rounded-full shadow-md transition-all">
                    <i class="fa-solid fa-plus mr-2"></i> จองคิวเลย
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach ($orders as $order)
                    <div
                        class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-md border border-gray-100 dark:border-slate-700 hover:shadow-lg transition-all relative overflow-hidden">

                        <div
                            class="absolute top-0 left-0 w-2 h-full 
                            @if ($order->status == 'pending') bg-yellow-400 
                            @elseif($order->status == 'picked_up') bg-blue-400 
                            @elseif($order->status == 'processing') bg-purple-400
                            @elseif($order->status == 'delivering') bg-indigo-400
                            @elseif($order->status == 'completed') bg-green-400
                            @else bg-red-400 @endif">
                        </div>

                        <div class="pl-4">
                            <div
                                class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 border-b border-gray-100 dark:border-slate-700 pb-4">
                                <div>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-1">
                                        <i class="fa-regular fa-calendar mr-1"></i>
                                        {{ \Carbon\Carbon::parse($order->created_at)->addYears(543)->format('d M Y H:i') }}
                                    </p>
                                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">
                                        <i class="fa-solid fa-receipt text-pink-400 mr-1"></i> {{ $order->order_number }}
                                    </h3>
                                </div>

                                <div>
                                    @if ($order->status == 'pending')
                                        <span
                                            class="bg-yellow-100 text-yellow-700 border border-yellow-200 px-4 py-1.5 rounded-full text-sm font-medium flex items-center gap-2 w-max">
                                            <i class="fa-solid fa-clock-rotate-left"></i> รอไรเดอร์เข้ารับผ้า
                                        </span>
                                    @elseif($order->status == 'picked_up')
                                        <span
                                            class="bg-blue-100 text-blue-700 border border-blue-200 px-4 py-1.5 rounded-full text-sm font-medium flex items-center gap-2 w-max">
                                            <i class="fa-solid fa-motorcycle"></i> ไรเดอร์รับผ้าแล้ว
                                        </span>
                                    @elseif($order->status == 'processing')
                                        <span
                                            class="bg-purple-100 text-purple-700 border border-purple-200 px-4 py-1.5 rounded-full text-sm font-medium flex items-center gap-2 w-max">
                                            <i class="fa-solid fa-jug-detergent"></i> กำลังซักและอบ
                                        </span>
                                    @elseif($order->status == 'delivering')
                                        <span
                                            class="bg-indigo-100 text-indigo-700 border border-indigo-200 px-4 py-1.5 rounded-full text-sm font-medium flex items-center gap-2 w-max">
                                            <i class="fa-solid fa-truck-fast"></i> กำลังนำส่งคืน
                                        </span>
                                    @elseif($order->status == 'completed')
                                        <span
                                            class="bg-green-100 text-green-700 border border-green-200 px-4 py-1.5 rounded-full text-sm font-medium flex items-center gap-2 w-max">
                                            <i class="fa-solid fa-check-double"></i> เสร็จสิ้นภารกิจ
                                        </span>
                                    @else
                                        <span
                                            class="bg-red-100 text-red-700 border border-red-200 px-4 py-1.5 rounded-full text-sm font-medium flex items-center gap-2 w-max">
                                            <i class="fa-solid fa-xmark"></i> ยกเลิกออเดอร์
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <p class="font-medium text-gray-700 dark:text-gray-300">
                                        {{ $order->package->name ?? 'ไม่พบข้อมูลแพ็กเกจ' }}</p>
                                    <div class="flex items-center gap-3 mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        <span><i class="fa-solid fa-droplet text-blue-400"></i> ซัก:
                                            {{ $order->wash_temp }}</span>
                                        <span><i class="fa-solid fa-wind text-orange-400"></i> อบ:
                                            {{ $order->dry_temp }}</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-400 mb-1">ยอดชำระ</p>
                                    <p class="text-xl font-extrabold text-pink-500">
                                        ฿{{ number_format($order->total_price) }}</p>

                                    <div class="mt-2">
                                        @if ($order->payment_status == 'paid')
                                            <span
                                                class="text-[10px] bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-2 py-1 rounded border border-green-200 dark:border-green-800 font-bold">
                                                <i class="fa-solid fa-circle-check"></i> ชำระเงินแล้ว
                                            </span>
                                        @elseif($order->payment_status == 'reviewing')
                                            <span
                                                class="text-[10px] bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 px-2 py-1 rounded border border-yellow-200 dark:border-yellow-800">
                                                <i class="fa-solid fa-spinner fa-spin"></i> รอตรวจสอบสลิป
                                            </span>
                                        @else
                                            <a href="{{ route('customer.orders.pay', $order->id) }}"
                                                class="inline-block bg-pink-500 hover:bg-pink-600 text-white text-[11px] font-bold py-1 px-3 rounded shadow-sm transition-colors">
                                                ชำระเงิน
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if ($order->logs->count() > 0)
                                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-slate-700">
                                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 mb-3"><i
                                            class="fa-solid fa-clock-rotate-left"></i> ประวัติสถานะ</p>
                                    <div class="space-y-3">
                                        @foreach ($order->logs->take(3) as $log)
                                            <div class="flex items-start gap-3">
                                                <div class="mt-1 w-2 h-2 rounded-full bg-pink-400 shrink-0"></div>
                                                <div>
                                                    <p class="text-sm text-gray-700 dark:text-gray-300">
                                                        @if ($log->new_status == 'picked_up')
                                                            ไรเดอร์รับผ้ามาแล้ว 🛵
                                                        @elseif($log->new_status == 'processing')
                                                            เริ่มทำการซักและอบ 🫧
                                                        @elseif($log->new_status == 'delivering')
                                                            กำลังเดินทางไปส่งคืน 🚚
                                                        @elseif($log->new_status == 'completed')
                                                            ส่งคืนสำเร็จเรียบร้อย! 🎉
                                                        @elseif($log->new_status == 'cancelled')
                                                            ออเดอร์ถูกยกเลิก ❌
                                                        @else
                                                            มีการอัปเดตสถานะ
                                                        @endif
                                                    </p>
                                                    <p class="text-[10px] text-gray-400">
                                                        {{ \Carbon\Carbon::parse($log->created_at)->format('d M H:i น.') }}
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
@endsection
