@extends('layouts.customer')

@section('title', 'แพ็กเกจบริการ - Washly')

@section('content')
<div class="max-w-6xl mx-auto pb-16 pt-4">
    
    @php
        // 📊 คำนวณหาแพ็กเกจที่นิยมที่สุดจากออเดอร์ทั้งหมด
        // ดึงเฉพาะ order ที่ไม่ได้โดนยกเลิก (cancelled) มานับ
        $popularPackageId = App\Models\Order::where('status', '!=', 'cancelled')
                            ->select('package_id', DB::raw('count(*) as total'))
                            ->groupBy('package_id')
                            ->orderByDesc('total')
                            ->first()?->package_id;
    @endphp

    <div class="text-center mb-12">
        <span class="inline-block px-4 py-1.5 mb-4 text-xs font-bold tracking-widest text-pink-500 uppercase bg-pink-50 dark:bg-pink-900/30 rounded-full">
            Our Services
        </span>
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-800 dark:text-gray-100 mb-4">
            เลือกแพ็กเกจที่ <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-rose-400">ใช่สำหรับคุณ</span>
        </h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 px-4">
        @forelse ($packages as $package)
            @php
                // เช็กว่า ID นี้คือ ID ที่ขายดีที่สุดหรือไม่
                $isPopular = ($package->id == $popularPackageId); 
            @endphp

            <div class="relative group {{ $isPopular ? 'scale-105 z-10' : '' }}">
                @if($isPopular)
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 z-20">
                        <span class="bg-gradient-to-r from-orange-400 to-pink-500 text-white text-[10px] font-bold px-4 py-1 rounded-full uppercase tracking-wider shadow-lg animate-pulse flex items-center gap-1">
                            <i class="fa-solid fa-fire text-[10px]"></i> ยอดนิยมอันดับ 1
                        </span>
                    </div>
                @endif

                <div class="h-full bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 border-2 {{ $isPopular ? 'border-pink-400 dark:border-pink-500 shadow-2xl shadow-pink-200 dark:shadow-none' : 'border-gray-100 dark:border-slate-700 shadow-sm' }} transition-all duration-300 hover:-translate-y-2 flex flex-col">
                    
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-14 h-14 rounded-2xl {{ $isPopular ? 'bg-pink-500 text-white' : 'bg-pink-50 dark:bg-slate-700 text-pink-500' }} flex items-center justify-center text-2xl shadow-sm group-hover:rotate-6 transition-transform">
                            @if($package->price <= 100)
                                <i class="fa-solid fa-shirt"></i>
                            @elseif($package->price <= 200)
                                <i class="fa-solid fa-basket-shopping"></i>
                            @else
                                <i class="fa-solid fa-jug-detergent"></i>
                            @endif
                        </div>
                        <div class="text-right">
                            <span class="text-sm text-gray-400 block">เริ่มต้นที่</span>
                            <span class="text-3xl font-black text-gray-800 dark:text-gray-100">฿{{ number_format($package->price) }}</span>
                        </div>
                    </div>

                    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-3">{{ $package->name }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed mb-6">
                        {{ $package->description ?: 'บริการซัก อบ พับ พร้อมรับ-ส่งถึงบ้านอย่างสะดวกสบาย มั่นใจในความสะอาด' }}
                    </p>

                    <ul class="space-y-3 mb-8 flex-grow">
                        <li class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
                            <i class="fa-solid fa-circle-check text-green-500"></i> ซักสะอาดด้วยน้ำยาพรีเมียม
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
                            <i class="fa-solid fa-circle-check text-green-500"></i> อบแห้งฆ่าเชื้อ 99.9%
                        </li>
                    </ul>

                    <a href="{{ route('customer.book') }}"
                        class="w-full flex items-center justify-center gap-2 {{ $isPopular ? 'bg-gradient-to-r from-pink-500 to-rose-500' : 'bg-gray-800 dark:bg-slate-700' }} hover:scale-[1.02] active:scale-95 text-white font-bold py-4 rounded-2xl shadow-lg transition-all">
                        <i class="fa-solid fa-basket-shopping"></i>
                        จองคิวแพ็กเกจนี้
                    </a>
                </div>
            </div>
        @empty
            @endforelse
    </div>
</div>
@endsection