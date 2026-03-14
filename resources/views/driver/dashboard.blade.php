@extends('layouts.driver')
@section('content')
<div class="py-2 sm:py-4 space-y-8">
    
    {{-- หัวข้อหลัก --}}
    <div class="flex items-center gap-3 mb-6 sm:mb-8">
        <div class="w-12 h-12 rounded-2xl washly-card shadow-sm flex items-center justify-center">
            <i class="fas fa-clipboard-list text-2xl washly-shell"></i>
        </div>
        <h1 class="text-2xl sm:text-3xl font-bold washly-shell">คิวงานวันนี้</h1>
    </div>

    @if(session('success'))
        <div class="washly-card p-4 rounded-xl flex items-center gap-3 shadow-sm border-l-4 border-l-emerald-400">
            <i class="fas fa-check-circle text-lg text-emerald-500"></i>
            <span class="font-medium washly-shell">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="washly-card p-4 rounded-xl flex items-center gap-3 shadow-sm border-l-4 border-l-rose-400">
            <i class="fas fa-times-circle text-lg text-rose-500"></i>
            <span class="font-medium washly-shell">{{ session('error') }}</span>
        </div>
    @endif

    {{-- โซนที่ 1: งานที่ฉันรับไว้ (My Active Jobs) --}}
    @if($myOrders->count() > 0)
    <div>
        {{-- 👇 แก้ไขตรงนี้: เปลี่ยนเป็น washly-shell --}}
        <h2 class="text-xl font-bold washly-shell mb-4 flex items-center gap-2">
            <i class="fas fa-motorcycle text-brand-blue-500"></i> งานของฉันที่กำลังทำ
        </h2>
        <div class="space-y-4">
            @foreach($myOrders as $order)
                <div class="washly-card p-5 rounded-2xl flex flex-col md:flex-row justify-between md:items-center gap-4 border border-blue-200 dark:border-blue-900 shadow-[0_0_15px_rgba(25,128,213,0.1)]">
                    <div class="flex-1">
                        <span class="washly-soft-chip px-3 py-1 text-xs font-semibold rounded-full washly-shell mb-2 inline-block">ออเดอร์ #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="font-bold washly-shell">{{ $order->user->fullname }}</h3>
                        <p class="text-sm opacity-80 washly-shell mt-1"><i class="fas fa-map-marker-alt text-rose-500 w-4"></i> {{ $order->user->address }}</p>
                    </div>
                    <div class="flex gap-2 w-full md:w-auto">
                        <form action="{{ route('driver.orders.status', $order->id) }}" method="POST" class="w-full m-0">
                            @csrf @method('PUT')
                            @if($order->status == 'picking_up')
                                <input type="hidden" name="status" value="processing">
                                <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl text-sm font-medium text-white shadow-md hover:opacity-90 transition">รับผ้าสำเร็จ</button>
                            @elseif($order->status == 'washing_completed')
                                <input type="hidden" name="status" value="delivering">
                                <button type="submit" class="w-full px-4 py-3 washly-brand-btn rounded-xl text-sm font-medium shadow-md">เริ่มจัดส่ง</button>
                            @elseif($order->status == 'delivering')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl text-sm font-medium text-white shadow-md hover:opacity-90 transition">ส่งมอบสำเร็จ</button>
                            @endif
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- โซนที่ 2: กระดานงานใหม่ (Available Jobs) --}}
    <div>
        {{-- 👇 แก้ไขตรงนี้: เปลี่ยนเป็น washly-shell --}}
        <h2 class="text-xl font-bold washly-shell mb-4 flex items-center gap-2">
            <i class="fas fa-satellite-dish text-brand-pink-400"></i> งานใหม่รอคนรับ
        </h2>
        <div class="space-y-4">
            @forelse($availableOrders as $order)
                <div class="washly-card p-5 rounded-2xl flex flex-col md:flex-row justify-between md:items-center gap-4 hover:-translate-y-1 transition-transform">
                    <div class="flex-1">
                        <div class="flex gap-2 mb-2">
                            <span class="washly-soft-chip px-3 py-1 text-xs font-semibold rounded-full washly-shell"><i class="fas fa-box text-orange-500"></i> รอรับผ้า</span>
                        </div>
                        <h3 class="font-bold washly-shell">{{ $order->user->fullname }}</h3>
                        <p class="text-sm opacity-80 washly-shell mt-1"><i class="fas fa-map-marker-alt text-rose-500 w-4"></i> {{ $order->user->address }}</p>
                    </div>
                    
                    <form action="{{ route('driver.orders.accept', $order->id) }}" method="POST" class="w-full md:w-auto m-0">
                        @csrf
                        <button type="submit" onclick="return confirm('รับงานนี้เลยไหมค้าบ?')" class="w-full px-6 py-3 washly-brand-btn rounded-xl text-sm font-medium shadow-md flex items-center justify-center gap-2">
                            <i class="fas fa-hand-paper text-white"></i> <span class="text-white">กดรับงาน</span>
                        </button>
                    </form>
                </div>
            @empty
                <div class="washly-card p-10 text-center rounded-3xl border-dashed border-2 opacity-70">
                    <i class="fas fa-coffee text-3xl washly-shell mb-3"></i>
                    <p class="washly-shell font-medium">กระดานว่างเปล่า นั่งพักก่อนค้าบ</p>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection