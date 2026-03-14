@extends('layouts.customer')

@section('title', 'จองคิวรับผ้า - Laundry Drop')

@section('content')
    <div class="max-w-6xl mx-auto pb-12 px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-10 pt-8">
            <div class="inline-flex items-center justify-center bg-pink-100 dark:bg-slate-700 text-pink-500 dark:text-pink-400 w-20 h-20 rounded-full mb-5 shadow-sm">
                <i class="fa-solid fa-motorcycle text-4xl"></i>
            </div>
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-gray-100 mb-3 tracking-tight">จองคิวให้ไรเดอร์ไปรับผ้า</h2>
            <p class="text-gray-500 dark:text-gray-400 text-lg">เลือกแพ็กเกจ, เมนูเสริม, และวิธีชำระเงินได้จบในที่เดียว</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl p-5 md:p-8 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700 transition-colors relative z-10">
            <form action="{{ route('customer.book.store') }}" method="POST" class="lg:grid lg:grid-cols-12 lg:gap-8 pb-24 lg:pb-0" id="booking-form">
                @csrf

                @if ($errors->any())
                    <div class="lg:col-span-12 mb-6 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800 rounded-2xl p-5 shadow-sm">
                        <div class="flex items-center gap-3 mb-2">
                            <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                            <p class="font-bold text-base">กรุณาตรวจสอบข้อมูลก่อนยืนยัน</p>
                        </div>
                        <ul class="list-disc list-inside space-y-1 ml-2 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="lg:col-span-8 space-y-8">

                    {{-- 1. เลือกแพ็กเกจ --}}
                    <div class="rounded-3xl border border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 md:p-8 shadow-sm">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="shrink-0 bg-gradient-to-br from-pink-400 to-pink-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold shadow-md text-lg">1</div>
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 tracking-tight">เลือกขนาดแพ็กเกจ</h3>
                        </div>

                        <div class="mb-6 pl-0 md:pl-14">
                            <div class="flex overflow-x-auto pb-3 hide-scrollbar gap-2 -mx-1 px-1 sm:mx-0 sm:px-0 sm:flex-wrap">
                                <button type="button" class="pkg-filter-btn shrink-0 whitespace-nowrap px-5 py-2 rounded-full text-sm font-bold bg-pink-500 text-white border border-transparent shadow-sm transition-all" data-filter="all">ทั้งหมด</button>
                                <button type="button" class="pkg-filter-btn shrink-0 whitespace-nowrap px-5 py-2 rounded-full text-sm font-bold bg-white text-gray-600 border border-gray-200 hover:bg-pink-50 hover:text-pink-600 hover:border-pink-200 transition-all" data-filter="9-10">9-10 kg</button>
                                <button type="button" class="pkg-filter-btn shrink-0 whitespace-nowrap px-5 py-2 rounded-full text-sm font-bold bg-white text-gray-600 border border-gray-200 hover:bg-pink-50 hover:text-pink-600 hover:border-pink-200 transition-all" data-filter="13-15">13-15 kg</button>
                                <button type="button" class="pkg-filter-btn shrink-0 whitespace-nowrap px-5 py-2 rounded-full text-sm font-bold bg-white text-gray-600 border border-gray-200 hover:bg-pink-50 hover:text-pink-600 hover:border-pink-200 transition-all" data-filter="18-20">18-20 kg</button>
                                <button type="button" class="pkg-filter-btn shrink-0 whitespace-nowrap px-5 py-2 rounded-full text-sm font-bold bg-white text-gray-600 border border-gray-200 hover:bg-pink-50 hover:text-pink-600 hover:border-pink-200 transition-all" data-filter="25-27">25-27 kg</button>
                                <button type="button" class="pkg-filter-btn shrink-0 whitespace-nowrap px-5 py-2 rounded-full text-sm font-bold bg-white text-gray-600 border border-gray-200 hover:bg-amber-50 hover:text-amber-600 hover:border-amber-200 transition-all" data-filter="พับ">แบบพับด้วย</button>
                            </div>
                        </div>

                        <div class="flex overflow-x-auto custom-scrollbar pb-6 pt-2 gap-5 pl-0 md:pl-14 snap-x">
                            @foreach ($packages as $package)
                                <label class="cursor-pointer relative group block h-full shrink-0 w-[260px] md:w-[280px] snap-start package-card-wrapper" data-name="{{ $package->name }}">
                                    <input type="radio" name="package_id" value="{{ $package->id }}"
                                        data-package-price="{{ (float) $package->price }}"
                                        data-default-addon-name="{{ $packageDefaultAddonMap[$package->id] ?? '' }}"
                                        class="peer sr-only package-option" required
                                        {{ old('package_id') == $package->id ? 'checked' : '' }}>

                                    <div class="h-full p-6 rounded-2xl border-2 border-gray-100 dark:border-slate-600 bg-gray-50/50 dark:bg-slate-700/30 hover:border-pink-300 dark:hover:border-pink-500 peer-checked:border-pink-500 peer-checked:bg-pink-50/50 dark:peer-checked:bg-slate-700/80 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-md text-center flex flex-col justify-between">
                                        <div>
                                            <div class="w-20 h-20 mx-auto bg-white dark:bg-slate-800 rounded-2xl flex items-center justify-center shadow-sm mb-4 transition-all peer-checked:shadow-pink-200 overflow-hidden border border-gray-100 dark:border-slate-600">
                                                @if ($package->image_path)
                                                    <img src="{{ asset('storage/' . $package->image_path) }}" class="w-full h-full object-cover">
                                                @else
                                                    <i class="fa-solid fa-shirt text-3xl text-gray-300 dark:text-gray-500 peer-checked:text-pink-500 transition-colors"></i>
                                                @endif
                                            </div>

                                            <h4 class="font-bold text-lg text-gray-800 dark:text-gray-100 mb-2">{{ $package->name }}</h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">{{ $package->description }}</p>
                                        </div>
                                        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-slate-600">
                                            <p class="text-2xl font-black text-pink-600 dark:text-pink-400">฿{{ number_format($package->price) }}</p>

                                            <div class="mt-3 pt-3 border-t border-dashed border-gray-200 dark:border-slate-600 flex items-center justify-between">
                                                <p class="text-[11px] font-bold text-gray-500 dark:text-gray-400">
                                                    เวลาอบเพิ่ม <span class="text-pink-500">+10฿</span>
                                                </p>
                                                <div class="flex items-center border border-gray-200 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-800 z-20 relative" onclick="event.preventDefault(); event.stopPropagation();">
                                                    <button type="button" class="pkg-qty-btn px-2.5 py-1 text-gray-500 hover:text-pink-500 transition-colors" data-step="-1" data-pkg="{{ $package->id }}">
                                                        <i class="fa-solid fa-minus text-[10px] pointer-events-none"></i>
                                                    </button>
                                                    <input type="number" name="extra_dry_qty[{{ $package->id }}]" id="extra-dry-{{ $package->id }}" value="0" min="0" max="10" class="w-6 text-center text-xs font-bold border-none p-0 focus:ring-0 bg-transparent pointer-events-none" readonly>
                                                    <button type="button" class="pkg-qty-btn px-2.5 py-1 text-gray-500 hover:text-pink-500 transition-colors" data-step="1" data-pkg="{{ $package->id }}">
                                                        <i class="fa-solid fa-plus text-[10px] pointer-events-none"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="absolute top-4 right-4 scale-0 opacity-0 peer-checked:scale-100 peer-checked:opacity-100 text-pink-500 transition-all duration-300">
                                            <i class="fa-solid fa-circle-check text-2xl bg-white dark:bg-slate-800 rounded-full shadow-sm"></i>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- 2. เลือกน้ำยาและบริการเสริม --}}
                    <div class="rounded-3xl border border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 md:p-8 shadow-sm">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="shrink-0 bg-gradient-to-br from-pink-400 to-pink-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold shadow-md text-lg">2</div>
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 tracking-tight">เลือกน้ำยาและบริการเสริม</h3>
                        </div>

                        <div class="pl-0 md:pl-14 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <label class="flex items-start gap-3 p-4 rounded-2xl border-2 border-transparent bg-gray-50 dark:bg-slate-700/50 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors cursor-pointer ring-1 ring-gray-200 dark:ring-slate-600 focus-within:ring-pink-400">
                                    <input type="checkbox" id="use_customer_detergent" name="use_customer_detergent" value="1" class="w-5 h-5 rounded border-gray-300 text-pink-500 focus:ring-pink-500 dark:border-slate-500 dark:bg-slate-800 mt-0.5" {{ old('use_customer_detergent') ? 'checked' : '' }}>
                                    <div class="flex-1">
                                        <p class="font-bold text-gray-800 dark:text-gray-100 text-sm">ลูกค้ามีน้ำยาซักผ้าเอง</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">ปิดตัวเลือกหมวดน้ำยาซัก และไม่คิดค่าส่วนนี้</p>
                                    </div>
                                </label>

                                <label class="flex items-start gap-3 p-4 rounded-2xl border-2 border-transparent bg-gray-50 dark:bg-slate-700/50 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors cursor-pointer ring-1 ring-gray-200 dark:ring-slate-600 focus-within:ring-pink-400">
                                    <input type="checkbox" id="use_customer_softener" name="use_customer_softener" value="1" class="w-5 h-5 rounded border-gray-300 text-pink-500 focus:ring-pink-500 dark:border-slate-500 dark:bg-slate-800 mt-0.5" {{ old('use_customer_softener') ? 'checked' : '' }}>
                                    <div class="flex-1">
                                        <p class="font-bold text-gray-800 dark:text-gray-100 text-sm">ลูกค้ามีน้ำยาปรับผ้านุ่มเอง</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">ปิดตัวเลือกหมวดน้ำยาปรับผ้านุ่ม และไม่คิดค่าส่วนนี้</p>
                                    </div>
                                </label>
                            </div>

                            <div class="rounded-2xl border border-blue-200 bg-blue-50/80 dark:bg-blue-900/20 dark:border-blue-800 p-4 text-sm text-blue-800 dark:text-blue-200 flex gap-3">
                                <i class="fa-solid fa-circle-info text-blue-500 dark:text-blue-400 mt-0.5 text-lg"></i>
                                <div>
                                    <p class="font-bold text-base mb-1">กรณีไม่มีน้ำยามาเอง</p>
                                    <p class="opacity-90 leading-relaxed">ระบบจะเลือกค่าเริ่มต้นอัตโนมัติแยกตามหมวดที่ลูกค้าไม่ได้ติ๊กว่ามีมาเอง</p>
                                    <ul class="mt-2 space-y-1 list-disc list-inside opacity-90">
                                        <li>สูตรน้ำยาซัก: <span id="default-addon-hint" class="font-semibold underline decoration-blue-300 underline-offset-2">ยังไม่ได้เลือกแพ็กเกจ</span></li>
                                        <li>น้ำยาปรับผ้านุ่ม: <span class="font-semibold">ใช้ค่าเริ่มต้นในระบบ</span></li>
                                    </ul>
                                </div>
                            </div>

                            <div id="chemicals-section" class="space-y-8 pt-2">
                                <div>
                                    <div class="flex items-center gap-2 mb-4">
                                        <i class="fa-solid fa-jug-detergent text-xl text-blue-400"></i>
                                        <h4 class="text-lg font-bold text-gray-800 dark:text-gray-100">น้ำยาซักผ้า</h4>
                                    </div>
                                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4" id="detergent-wrapper">
                                        @foreach ($detergentAddons as $addon)
                                            @php
                                                $addonKey = $addon['code'];
                                                $oldQty = old('addons.' . $addonKey . '.qty', 1);
                                                $checked = old('addons.' . $addonKey . '.code') === $addon['code'];
                                            @endphp
                                            <div class="addon-card relative p-4 rounded-2xl border-2 border-gray-100 dark:border-slate-600 bg-white dark:bg-slate-800 hover:border-pink-300 dark:hover:border-pink-500 transition-all duration-300 cursor-pointer shadow-sm hover:shadow-md flex flex-col h-full justify-between gap-4"
                                                data-category="{{ $addon['category'] }}" data-addon-price="{{ (float) $addon['price'] }}">
                                                <div class="flex items-start gap-3 w-full">
                                                    <div class="shrink-0 pt-1">
                                                        <input type="checkbox" name="addons[{{ $addonKey }}][code]" value="{{ $addon['code'] }}" class="addon-check w-5 h-5 rounded border-gray-300 text-pink-500 focus:ring-pink-500 transition-colors cursor-pointer" {{ $checked ? 'checked' : '' }}>
                                                    </div>
                                                    <div class="shrink-0">
                                                        @if (!empty($addon['image_path']))
                                                            <img src="{{ asset('storage/' . $addon['image_path']) }}" alt="{{ $addon['name'] }}" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border border-gray-100 dark:border-slate-600 shadow-sm">
                                                        @else
                                                            <div class="w-16 h-16 md:w-20 md:h-20 rounded-xl border border-gray-100 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 flex items-center justify-center shadow-sm">
                                                                <i class="fa-solid fa-box text-gray-300 dark:text-gray-500 text-xl md:text-2xl"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0 break-words">
                                                        <p class="addon-name-txt font-bold text-gray-800 dark:text-gray-100 text-sm md:text-base leading-snug mb-1">{{ $addon['name'] }}</p>
                                                        <p class="text-sm font-semibold text-pink-500 dark:text-pink-400">+ ฿{{ number_format($addon['price']) }} <span class="text-xs text-gray-500 dark:text-gray-400 font-normal">/หน่วย</span></p>
                                                    </div>
                                                </div>
                                                <div class="flex justify-end items-end mt-auto pt-2">
                                                    <div class="flex items-center border-2 border-gray-100 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 overflow-hidden shadow-sm" onclick="event.stopPropagation()">
                                                        <button type="button" class="qty-step w-9 h-9 md:w-10 md:h-10 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors flex items-center justify-center" data-step="-1"><i class="fa-solid fa-minus text-xs"></i></button>
                                                        <input type="number" min="1" max="10" name="addons[{{ $addonKey }}][qty]" value="{{ $oldQty }}" class="addon-qty w-10 md:w-12 h-9 md:h-10 p-0 text-center border-x border-y-0 border-gray-100 dark:border-slate-600 bg-gray-50 dark:bg-slate-800 font-bold text-gray-800 dark:text-gray-100 focus:ring-0 text-sm md:text-base" {{ $checked ? '' : 'disabled' }}>
                                                        <button type="button" class="qty-step w-9 h-9 md:w-10 md:h-10 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors flex items-center justify-center" data-step="1"><i class="fa-solid fa-plus text-xs"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-gray-100 dark:border-slate-700">
                                    <div class="flex items-center gap-2 mb-4">
                                        <i class="fa-solid fa-bottle-droplet text-xl text-pink-400"></i>
                                        <h4 class="text-lg font-bold text-gray-800 dark:text-gray-100">น้ำยาปรับผ้านุ่ม</h4>
                                    </div>
                                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4" id="softener-wrapper">
                                        @foreach ($softenerAddons as $addon)
                                            @php
                                                $addonKey = $addon['code'];
                                                $oldQty = old('addons.' . $addonKey . '.qty', 1);
                                                $checked = old('addons.' . $addonKey . '.code') === $addon['code'];
                                            @endphp
                                            <div class="addon-card relative p-4 rounded-2xl border-2 border-gray-100 dark:border-slate-600 bg-white dark:bg-slate-800 hover:border-pink-300 dark:hover:border-pink-500 transition-all duration-300 cursor-pointer shadow-sm hover:shadow-md flex flex-col h-full justify-between gap-4"
                                                data-category="{{ $addon['category'] }}" data-addon-price="{{ (float) $addon['price'] }}">
                                                <div class="flex items-start gap-3 w-full">
                                                    <div class="shrink-0 pt-1">
                                                        <input type="checkbox" name="addons[{{ $addonKey }}][code]" value="{{ $addon['code'] }}" class="addon-check w-5 h-5 rounded border-gray-300 text-pink-500 focus:ring-pink-500 transition-colors cursor-pointer" {{ $checked ? 'checked' : '' }}>
                                                    </div>
                                                    <div class="shrink-0">
                                                        @if (!empty($addon['image_path']))
                                                            <img src="{{ asset('storage/' . $addon['image_path']) }}" alt="{{ $addon['name'] }}" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border border-gray-100 dark:border-slate-600 shadow-sm">
                                                        @else
                                                            <div class="w-16 h-16 md:w-20 md:h-20 rounded-xl border border-gray-100 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 flex items-center justify-center shadow-sm">
                                                                <i class="fa-solid fa-box text-gray-300 dark:text-gray-500 text-xl md:text-2xl"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0 break-words">
                                                        <p class="addon-name-txt font-bold text-gray-800 dark:text-gray-100 text-sm md:text-base leading-snug mb-1">{{ $addon['name'] }}</p>
                                                        <p class="text-sm font-semibold text-pink-500 dark:text-pink-400">+ ฿{{ number_format($addon['price']) }} <span class="text-xs text-gray-500 dark:text-gray-400 font-normal">/หน่วย</span></p>
                                                    </div>
                                                </div>
                                                <div class="flex justify-end items-end mt-auto pt-2">
                                                    <div class="flex items-center border-2 border-gray-100 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 overflow-hidden shadow-sm" onclick="event.stopPropagation()">
                                                        <button type="button" class="qty-step w-9 h-9 md:w-10 md:h-10 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors flex items-center justify-center" data-step="-1"><i class="fa-solid fa-minus text-xs"></i></button>
                                                        <input type="number" min="1" max="10" name="addons[{{ $addonKey }}][qty]" value="{{ $oldQty }}" class="addon-qty w-10 md:w-12 h-9 md:h-10 p-0 text-center border-x border-y-0 border-gray-100 dark:border-slate-600 bg-gray-50 dark:bg-slate-800 font-bold text-gray-800 dark:text-gray-100 focus:ring-0 text-sm md:text-base" {{ $checked ? '' : 'disabled' }}>
                                                        <button type="button" class="qty-step w-9 h-9 md:w-10 md:h-10 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors flex items-center justify-center" data-step="1"><i class="fa-solid fa-plus text-xs"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="pt-6 border-t border-gray-100 dark:border-slate-700 mt-6">
                                    <div class="flex items-center gap-2 mb-4">
                                        <i class="fa-solid fa-sparkles text-xl text-amber-400"></i>
                                        <h4 class="text-lg font-bold text-gray-800 dark:text-gray-100">บริการเสริมอื่นๆ</h4>
                                    </div>
                                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4" id="service-wrapper">
                                        @foreach ($serviceAddons as $addon)
                                            @php
                                                $addonKey = $addon['code'];
                                                $oldQty = old('addons.' . $addonKey . '.qty', 1);
                                                $checked = old('addons.' . $addonKey . '.code') === $addon['code'];
                                            @endphp
                                            <div class="addon-card relative p-4 rounded-2xl border-2 border-gray-100 dark:border-slate-600 bg-white dark:bg-slate-800 hover:border-pink-300 dark:hover:border-pink-500 transition-all duration-300 cursor-pointer shadow-sm hover:shadow-md flex flex-col h-full justify-between gap-4"
                                                data-category="{{ $addon['category'] }}" data-addon-price="{{ (float) $addon['price'] }}">
                                                <div class="flex items-start gap-3 w-full">
                                                    <div class="shrink-0 pt-1">
                                                        <input type="checkbox" name="addons[{{ $addonKey }}][code]" value="{{ $addon['code'] }}" class="addon-check w-5 h-5 rounded border-gray-300 text-pink-500 focus:ring-pink-500 transition-colors cursor-pointer" {{ $checked ? 'checked' : '' }}>
                                                    </div>
                                                    <div class="shrink-0">
                                                        @if (!empty($addon['image_path']))
                                                            <img src="{{ asset('storage/' . $addon['image_path']) }}" alt="{{ $addon['name'] }}" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border border-gray-100 dark:border-slate-600 shadow-sm">
                                                        @else
                                                            <div class="w-16 h-16 md:w-20 md:h-20 rounded-xl border border-gray-100 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 flex items-center justify-center shadow-sm">
                                                                <i class="fa-solid fa-box text-gray-300 dark:text-gray-500 text-xl md:text-2xl"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0 break-words">
                                                        <p class="addon-name-txt font-bold text-gray-800 dark:text-gray-100 text-sm md:text-base leading-snug mb-1">{{ $addon['name'] }}</p>
                                                        <p class="text-sm font-semibold text-pink-500 dark:text-pink-400">+ ฿{{ number_format($addon['price']) }} <span class="text-xs text-gray-500 dark:text-gray-400 font-normal">/หน่วย</span></p>
                                                    </div>
                                                </div>
                                                <div class="flex justify-end items-end mt-auto pt-2">
                                                    <div class="flex items-center border-2 border-gray-100 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 overflow-hidden shadow-sm" onclick="event.stopPropagation()">
                                                        <button type="button" class="qty-step w-9 h-9 md:w-10 md:h-10 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors flex items-center justify-center" data-step="-1"><i class="fa-solid fa-minus text-xs"></i></button>
                                                        <input type="number" min="1" max="10" name="addons[{{ $addonKey }}][qty]" value="{{ $oldQty }}" class="addon-qty w-10 md:w-12 h-9 md:h-10 p-0 text-center border-x border-y-0 border-gray-100 dark:border-slate-600 bg-gray-50 dark:bg-slate-800 font-bold text-gray-800 dark:text-gray-100 focus:ring-0 text-sm md:text-base" {{ $checked ? '' : 'disabled' }}>
                                                        <button type="button" class="qty-step w-9 h-9 md:w-10 md:h-10 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors flex items-center justify-center" data-step="1"><i class="fa-solid fa-plus text-xs"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 🌟 3. วันที่และรอบเวลา (ดีไซน์ใหม่) --}}
                    <div class="rounded-3xl border border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 md:p-8 shadow-sm">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="shrink-0 bg-gradient-to-br from-pink-400 to-pink-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold shadow-md text-lg">3</div>
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 tracking-tight">วันที่และรอบเวลารับผ้า</h3>
                        </div>

                        {{-- เลือกวันที่ --}}
                        <div class="mb-8 pl-0 md:pl-14">
                            <label class="block font-bold text-gray-700 dark:text-gray-300 mb-3">ต้องการให้ไรเดอร์เข้ารับผ้าวันไหน? <span class="text-rose-500">*</span></label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- วันนี้ --}}
                                <label class="cursor-pointer relative group">
                                    <input type="radio" name="pickup_date" value="today" class="peer sr-only pickup-date-radio" required @if($isClosedToday) disabled @else {{ old('pickup_date', 'today') == 'today' ? 'checked' : '' }} @endif>
                                    <div class="p-4 rounded-2xl border-2 border-gray-100 dark:border-slate-600 @if($isClosedToday) bg-gray-100 dark:bg-slate-800 opacity-60 cursor-not-allowed @else bg-gray-50/50 hover:border-pink-300 peer-checked:border-pink-500 peer-checked:bg-pink-50 @endif transition-all duration-200">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="font-bold text-gray-800 dark:text-gray-100 text-base">วันนี้</p>
                                                <p class="text-xs text-gray-500">{{ $today->locale('th')->translatedFormat('j F Y') }}</p>
                                            </div>
                                            @if($isClosedToday)
                                                <span class="bg-rose-100 text-rose-600 text-[10px] px-2 py-1 rounded-md font-bold border border-rose-200">หมดเวลาจอง</span>
                                            @else
                                                <i class="fa-solid fa-check-circle text-xl text-pink-500 scale-0 opacity-0 peer-checked:scale-100 peer-checked:opacity-100 transition-all"></i>
                                            @endif
                                        </div>
                                    </div>
                                </label>

                                {{-- พรุ่งนี้ --}}
                                <label class="cursor-pointer relative group">
                                    <input type="radio" name="pickup_date" value="tomorrow" class="peer sr-only pickup-date-radio" required {{ old('pickup_date') == 'tomorrow' || $isClosedToday ? 'checked' : '' }}>
                                    <div class="p-4 rounded-2xl border-2 border-gray-100 dark:border-slate-600 bg-gray-50/50 hover:border-pink-300 peer-checked:border-pink-500 peer-checked:bg-pink-50 transition-all duration-200">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="font-bold text-blue-600 dark:text-blue-400 text-base flex items-center gap-2">พรุ่งนี้ <span class="bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 text-[10px] px-2 py-0.5 rounded-full border border-blue-200 dark:border-blue-800">จองล่วงหน้า</span></p>
                                                <p class="text-xs text-gray-500">{{ $tomorrow->locale('th')->translatedFormat('j F Y') }}</p>
                                            </div>
                                            <i class="fa-solid fa-check-circle text-xl text-pink-500 scale-0 opacity-0 peer-checked:scale-100 peer-checked:opacity-100 transition-all"></i>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- เลือกรอบเวลา --}}
                        <div class="pl-0 md:pl-14">
                            <label class="block font-bold text-gray-700 dark:text-gray-300 mb-3">เลือกรอบเวลา <span class="text-rose-500">*</span></label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach ($timeSlots as $slot)
                                    <label class="cursor-pointer relative group">
                                        <input type="radio" name="time_slot_id" value="{{ $slot->id }}" class="peer sr-only time-slot-radio" required {{ old('time_slot_id') == $slot->id ? 'checked' : '' }}>
                                        <div class="px-5 py-4 rounded-2xl border-2 border-gray-100 dark:border-slate-600 bg-gray-50/50 dark:bg-slate-700/30 hover:border-pink-300 peer-checked:border-pink-500 peer-checked:bg-pink-50 dark:peer-checked:bg-slate-700 transition-all duration-200 flex items-center justify-between group-hover:shadow-sm">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-white dark:bg-slate-800 flex items-center justify-center shadow-sm text-gray-400 peer-checked:text-pink-500 transition-colors">
                                                    <i class="flex-shrink-0 fa-regular fa-clock"></i>
                                                </div>
                                                <span class="font-bold text-gray-700 dark:text-gray-200 slot-name-text">{{ $slot->round_name }}</span>
                                            </div>
                                            <i class="fa-solid fa-check-circle text-xl text-pink-500 scale-0 opacity-0 peer-checked:scale-100 peer-checked:opacity-100 transition-all duration-300"></i>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- เช็กสถานะคิว (รวมวันนี้และพรุ่งนี้) --}}
                        @php
                            $allQueues = $todayQueues->map(function($q) { $q->date_group = 'today'; return $q; })
                                ->concat($tomorrowQueues->map(function($q) { $q->date_group = 'tomorrow'; return $q; }));
                        @endphp

                        <div class="pl-0 md:pl-14 mt-8 pt-6 border-t border-dashed border-gray-200 dark:border-slate-700">
                            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                                <h4 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                                    <i class="fa-solid fa-users text-pink-500"></i> สถานะคิวของวันที่เลือก
                                </h4>
                                <span id="queue-counter-badge" class="text-sm font-semibold bg-pink-100 text-pink-700 dark:bg-slate-700 dark:text-pink-400 px-3 py-1.5 rounded-full shadow-sm">
                                    เลือกรอบเวลาเพื่อดูคิว
                                </span>
                            </div>

                            @if($allQueues->isEmpty())
                                <div class="text-center py-5 bg-gray-50 dark:bg-slate-700/30 rounded-2xl border border-dashed border-gray-200 dark:border-slate-600">
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">ยังไม่มีคิวจอง 🎉 คุณสามารถจองเป็นคิวแรกได้เลย!</p>
                                </div>
                            @else
                                <div id="queue-list-container" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach($allQueues as $index => $queue)
                                        <div class="queue-item flex items-center gap-3 p-3 rounded-2xl border transition-colors {{ $queue->user_id == Auth::id() ? 'border-pink-300 bg-pink-50/50 dark:bg-slate-700/80 dark:border-pink-500/50' : 'border-gray-100 dark:border-slate-600 bg-gray-50 dark:bg-slate-700/30' }}" data-slot-id="{{ $queue->time_slot_id }}" data-date="{{ $queue->date_group }}">
                                            <div class="shrink-0 w-8 h-8 {{ $queue->user_id == Auth::id() ? 'bg-pink-500 text-white' : 'bg-white dark:bg-slate-800 text-gray-500 dark:text-gray-300' }} rounded-full flex items-center justify-center font-bold text-xs shadow-sm border border-gray-200 dark:border-slate-600">
                                                <i class="fa-solid fa-user text-[10px]"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="font-bold text-sm text-gray-800 dark:text-gray-100 truncate">
                                                    @if($queue->user_id == Auth::id())
                                                        <span class="text-pink-600 dark:text-pink-400">คิวของคุณ</span>
                                                    @else
                                                        คุณ {{ mb_substr($queue->user->fullname ?? 'ลูกค้า', 0, 3) }}***
                                                    @endif
                                                </p>
                                                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">
                                                    <i class="fa-regular fa-clock"></i> {{ $queue->timeSlot->round_name ?? 'ไม่ระบุ' }}
                                                </p>
                                            </div>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-400',
                                                    'picked_up' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400',
                                                    'processing' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-400',
                                                    'delivering' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-400',
                                                    'completed' => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400',
                                                ];
                                                $statusLabels = [
                                                    'pending' => 'รอรับ', 'picked_up' => 'รับแล้ว', 'processing' => 'ซัก/อบ',
                                                    'delivering' => 'กำลังส่ง', 'completed' => 'เสร็จ',
                                                ];
                                            @endphp
                                            <div class="shrink-0">
                                                <span class="text-[10px] font-bold px-2 py-1 rounded-md {{ $statusColors[$queue->status] ?? 'bg-gray-100 text-gray-600' }}">
                                                    {{ $statusLabels[$queue->status] ?? 'รอคิว' }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div id="empty-slot-message" class="hidden text-center py-5 bg-blue-50 dark:bg-blue-900/20 rounded-2xl border border-blue-200 dark:border-blue-800 mt-3">
                                    <i class="fa-solid fa-sparkles text-blue-500 mb-2 text-xl"></i>
                                    <p class="text-blue-800 dark:text-blue-300 text-sm font-medium">รอบเวลานี้ยังว่างอยู่! คุณสามารถจองเป็นคิวแรกของรอบนี้ได้เลย</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- 4. วิธีชำระเงิน --}}
                    <div class="rounded-3xl border border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 md:p-8 shadow-sm">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="shrink-0 bg-gradient-to-br from-pink-400 to-pink-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold shadow-md text-lg">4</div>
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 tracking-tight">วิธีชำระเงิน</h3>
                        </div>

                        <div class="pl-0 md:pl-14 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="cursor-pointer group">
                                <input type="radio" name="payment_method" value="transfer" class="sr-only peer" {{ old('payment_method', 'transfer') === 'transfer' ? 'checked' : '' }}>
                                <div class="rounded-2xl border-2 border-gray-100 dark:border-slate-600 p-5 bg-gray-50/50 dark:bg-slate-700/30 peer-checked:border-pink-500 peer-checked:bg-pink-50/50 dark:peer-checked:bg-slate-700 hover:border-pink-300 transition-all duration-200 flex items-center justify-between group-hover:shadow-sm">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-white dark:bg-slate-800 rounded-xl flex items-center justify-center shadow-sm text-blue-500">
                                            <i class="fa-solid fa-qrcode text-2xl"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-800 dark:text-gray-100">โอนเงิน / สแกน QR</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">แนบสลิปในระบบ</p>
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-circle-check text-2xl text-pink-500 scale-0 opacity-0 peer-checked:scale-100 peer-checked:opacity-100 transition-all duration-300"></i>
                                </div>
                            </label>

                            <label class="cursor-pointer group">
                                <input type="radio" name="payment_method" value="cash" class="sr-only peer" {{ old('payment_method') === 'cash' ? 'checked' : '' }}>
                                <div class="rounded-2xl border-2 border-gray-100 dark:border-slate-600 p-5 bg-gray-50/50 dark:bg-slate-700/30 peer-checked:border-pink-500 peer-checked:bg-pink-50/50 dark:peer-checked:bg-slate-700 hover:border-pink-300 transition-all duration-200 flex items-center justify-between group-hover:shadow-sm">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-white dark:bg-slate-800 rounded-xl flex items-center justify-center shadow-sm text-green-500">
                                            <i class="fa-solid fa-money-bill-wave text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-800 dark:text-gray-100">เงินสดปลายทาง</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">จ่ายกับไรเดอร์</p>
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-circle-check text-2xl text-pink-500 scale-0 opacity-0 peer-checked:scale-100 peer-checked:opacity-100 transition-all duration-300"></i>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- 5. สถานที่ และ หมายเหตุ --}}
                    <div class="rounded-3xl border border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 md:p-8 shadow-sm">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="shrink-0 bg-gradient-to-br from-pink-400 to-pink-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold shadow-md text-lg">5</div>
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 tracking-tight">สถานที่ และ หมายเหตุ</h3>
                        </div>

                        <div class="pl-0 md:pl-14 space-y-5">
                            <div>
                                <label class="block font-bold text-gray-700 dark:text-gray-300 mb-2">ที่อยู่ปัจจุบันสำหรับรับ-ส่งผ้า <span class="text-rose-500">*</span></label>
                                
                                <button type="button" onclick="getLocation()" id="btn-get-location" class="mb-3 flex items-center gap-2 bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-100 px-4 py-2 rounded-xl transition-colors text-sm font-bold">
                                    <i class="fa-solid fa-location-crosshairs"></i> ดึงตำแหน่งปัจจุบันของฉัน (GPS)
                                </button>

                                <textarea id="pickup_address" name="pickup_address" rows="3" required placeholder="ระบุบ้านเลขที่, ชื่อหมู่บ้าน, คอนโด, ชั้น, ห้อง..." class="w-full p-4 rounded-2xl border-2 border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700/50 text-gray-800 dark:text-gray-100 focus:border-pink-500 focus:ring-4 focus:ring-pink-500/10 outline-none transition-all resize-none leading-relaxed">{{ old('pickup_address', Auth::user()->address) }}</textarea>

                                <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', Auth::user()->latitude) }}">
                                <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', Auth::user()->longitude) }}">

                                <p id="location-status" class="mt-2 text-sm text-gray-500 font-medium"></p>
                            </div>

                            <hr class="border-gray-100 dark:border-slate-700 my-4">

                            <div>
                                <label class="block font-bold text-gray-700 dark:text-gray-300 mb-2">หมายเหตุ / คำขอพิเศษ (ถ้ามี)</label>
                                <textarea name="customer_note" rows="2" placeholder="เช่น ฝากไว้ที่นิติบุคคล, รบกวนแยกซักผ้าสีขาว, ฯลฯ" class="w-full p-4 rounded-2xl border-2 border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700/50 text-gray-800 dark:text-gray-100 focus:border-pink-500 focus:ring-4 focus:ring-pink-500/10 outline-none transition-all resize-none leading-relaxed">{{ old('customer_note') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 🛒 สรุปรายการแบบละเอียด (Dynamic List) --}}
                <aside class="lg:col-span-4 mt-8 lg:mt-0 relative">
                    <div class="lg:sticky lg:top-24 space-y-5">
                        <div class="rounded-3xl bg-white dark:bg-slate-800 border-2 border-pink-100 dark:border-slate-700 shadow-xl shadow-pink-100/50 dark:shadow-none flex flex-col min-h-[400px]">
                            <div class="bg-pink-50 dark:bg-slate-700/50 px-6 py-4 border-b border-pink-100 dark:border-slate-600 shrink-0">
                                <h4 class="font-bold text-lg text-pink-600 dark:text-pink-400 flex items-center gap-2">
                                    <i class="fa-solid fa-receipt"></i> สรุปรายการจองของคุณ
                                </h4>
                            </div>
                            
                            <div class="p-6 flex flex-col flex-1">
                                <div id="summary-items-list" class="space-y-3 mb-6 flex-1 overflow-y-auto pr-2 custom-scrollbar">
                                    <div class="text-sm text-gray-400 dark:text-gray-500 italic text-center py-4">
                                        กรุณาเลือกแพ็กเกจเพื่อดูสรุปรายการ
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-dashed border-gray-200 dark:border-slate-600 flex justify-between items-end shrink-0">
                                    <span class="font-bold text-gray-700 dark:text-gray-200 pb-1">ยอดชำระสุทธิ</span>
                                    <span id="summary-total" class="text-3xl font-black text-pink-600 dark:text-pink-400">฿0</span>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-2xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 p-4 text-sm text-amber-800 dark:text-amber-200 flex gap-3 shadow-sm">
                            <i class="fa-solid fa-shield-check text-amber-500 mt-0.5 text-lg"></i>
                            <p class="leading-relaxed">โปรดตรวจสอบแพ็กเกจ รอบเวลา และที่อยู่ให้ถูกต้องครบถ้วนก่อนกดยืนยันการจอง</p>
                        </div>

                        <button type="submit" class="hidden lg:flex w-full bg-gradient-to-r from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 text-white text-xl font-bold py-5 rounded-2xl shadow-lg shadow-pink-500/30 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 justify-center items-center gap-3">
                            ยืนยันการจองคิว <i class="fa-solid fa-arrow-right-long text-xl"></i>
                        </button>
                    </div>
                </aside>

                <div class="fixed bottom-0 left-0 right-0 z-50 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border-t border-gray-200 dark:border-slate-700 p-4 lg:hidden shadow-[0_-10px_15px_-3px_rgba(0,0,0,0.05)]">
                    <div class="max-w-6xl mx-auto flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">ยอดชำระสุทธิ</p>
                            <p id="summary-total-mobile" class="text-2xl font-black text-pink-600 dark:text-pink-400 leading-none mt-1">฿0</p>
                        </div>
                        <button type="submit" class="bg-gradient-to-r from-pink-500 to-pink-600 text-white font-bold text-lg px-8 py-3.5 rounded-2xl shadow-lg shadow-pink-500/30 active:scale-95 transition-all w-1/2 flex items-center justify-center gap-2">
                            จองคิวเลย
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        .addon-qty::-webkit-outer-spin-button, .addon-qty::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        .addon-qty { -moz-appearance: textfield; appearance: textfield; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .custom-scrollbar::-webkit-scrollbar { height: 10px; width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f8fafc; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; cursor: pointer; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #f472b6; }
        .dark .custom-scrollbar::-webkit-scrollbar-track { background: #1e293b; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #475569; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #f472b6; }
    </style>

    <script>
        const packageOptions = Array.from(document.querySelectorAll('.package-option'));
        const addonChecks = Array.from(document.querySelectorAll('.addon-check'));
        const useCustomerDetergent = document.getElementById('use_customer_detergent');
        const useCustomerSoftener = document.getElementById('use_customer_softener');

        const summaryItemsList = document.getElementById('summary-items-list');
        const totalEl = document.getElementById('summary-total');
        const totalMobileEl = document.getElementById('summary-total-mobile');
        
        function parseNum(value) { return Number.isFinite(Number(value)) ? Number(value) : 0; }
        function formatBaht(value) { return `฿${parseNum(value).toLocaleString('th-TH')}`; }

        function recalculateSummary() {
            let grandTotal = 0;
            let listHTML = '';

            const selectedPackage = packageOptions.find((option) => option.checked);
            if (selectedPackage) {
                const subtotal = parseNum(selectedPackage.dataset.packagePrice);
                const packageName = selectedPackage.closest('.package-card-wrapper').dataset.name;
                grandTotal += subtotal;
                
                listHTML += `
                    <div class="flex justify-between items-start text-sm text-gray-800 dark:text-gray-100 font-bold mb-2 pb-2 border-b border-gray-100 dark:border-slate-700">
                        <span class="pr-4 flex items-start gap-2"><i class="fa-solid fa-box text-pink-500 mt-1"></i> ${packageName}</span>
                        <span class="whitespace-nowrap text-pink-600 dark:text-pink-400">฿${subtotal.toLocaleString()}</span>
                    </div>
                `;

                const defaultAddonHint = document.getElementById('default-addon-hint');
                if (defaultAddonHint) {
                    defaultAddonHint.textContent = selectedPackage.dataset.defaultAddonName || 'ใช้สูตรเริ่มต้นของระบบ';
                }

                const pkgId = selectedPackage.value;
                const extraQtyInput = document.getElementById(`extra-dry-${pkgId}`);
                if (extraQtyInput) {
                    const extraQty = parseNum(extraQtyInput.value);
                    if (extraQty > 0) {
                        const extraDryTotal = extraQty * 10;
                        grandTotal += extraDryTotal;
                        listHTML += `
                            <div class="flex justify-between items-start text-sm text-amber-600 dark:text-amber-400 mb-2 pl-6">
                                <span class="pr-4">+ อบผ้าเพิ่ม (${extraQty * 10} นาที)</span>
                                <span class="font-medium whitespace-nowrap">฿${extraDryTotal.toLocaleString()}</span>
                            </div>
                        `;
                    }
                }
            } else {
                const defaultAddonHint = document.getElementById('default-addon-hint');
                if (defaultAddonHint) defaultAddonHint.textContent = 'ยังไม่ได้เลือกแพ็กเกจ';
                listHTML += `<div class="text-sm text-gray-400 dark:text-gray-500 italic text-center py-4">กรุณาเลือกแพ็กเกจเพื่อดูสรุปรายการ</div>`;
            }

            addonChecks.forEach((check) => {
                if (!check.checked || check.disabled) return;
                const card = check.closest('.addon-card');
                const qtyInput = card?.querySelector('.addon-qty');
                const price = parseNum(card?.dataset.addonPrice);
                const qty = parseNum(qtyInput?.value || 1);
                const addonName = card.querySelector('.addon-name-txt').innerText;
                const lineTotal = price * Math.max(1, qty);
                grandTotal += lineTotal;

                listHTML += `
                    <div class="flex justify-between items-start text-sm text-gray-600 dark:text-gray-300 mb-2 pl-6">
                        <span class="pr-4">- ${addonName} <span class="text-[10px] bg-gray-100 dark:bg-slate-700 px-1.5 py-0.5 rounded ml-1">x${qty}</span></span>
                        <span class="font-medium whitespace-nowrap">฿${lineTotal.toLocaleString()}</span>
                    </div>
                `;
            });

            summaryItemsList.innerHTML = listHTML;
            totalEl.textContent = formatBaht(grandTotal);
            if (totalMobileEl) totalMobileEl.textContent = formatBaht(grandTotal);
        }

        function toggleAddonInput(checkEl) {
            const card = checkEl.closest('.addon-card');
            const qtyInput = card?.querySelector('.addon-qty');
            if (!qtyInput) return;
            qtyInput.disabled = !checkEl.checked;
            card?.classList.toggle('border-pink-400', checkEl.checked);
            card?.classList.toggle('bg-pink-50/30', checkEl.checked);
        }

        function applyOwnershipToggle(category, hasOwn) {
            document.querySelectorAll(`.addon-card[data-category="${category}"]`).forEach((card) => {
                const check = card.querySelector('.addon-check');
                const qty = card.querySelector('.addon-qty');
                if (!check || !qty) return;
                if (hasOwn) {
                    check.checked = false; check.disabled = true; qty.disabled = true;
                    card.classList.add('opacity-50', 'pointer-events-none');
                    card.classList.remove('border-pink-400', 'bg-pink-50/30');
                } else {
                    check.disabled = false; card.classList.remove('opacity-50', 'pointer-events-none');
                    qty.disabled = !check.checked;
                    card.classList.toggle('border-pink-400', check.checked);
                    card.classList.toggle('bg-pink-50/30', check.checked);
                }
            });
        }

        function enforceChemicalToggles() {
            applyOwnershipToggle('detergent', !!useCustomerDetergent?.checked);
            applyOwnershipToggle('softener', !!useCustomerSoftener?.checked);
        }

        document.querySelectorAll('.addon-card').forEach((card) => {
            card.addEventListener('click', (event) => {
                if (event.target.closest('.addon-qty') || event.target.closest('.qty-step') || event.target.closest('.addon-check')) return;
                const check = card.querySelector('.addon-check');
                if (!check || check.disabled) return;
                check.checked = !check.checked;
                toggleAddonInput(check);
                recalculateSummary();
            });
        });

        document.querySelectorAll('.qty-step').forEach((btn) => {
            btn.addEventListener('click', (event) => {
                event.preventDefault(); event.stopPropagation();
                const card = btn.closest('.addon-card');
                const qtyInput = card?.querySelector('.addon-qty');
                const check = card?.querySelector('.addon-check');
                if (!qtyInput || !check || check.disabled) return;
                if (!check.checked) { check.checked = true; toggleAddonInput(check); }
                const step = parseNum(btn.dataset.step || 1);
                const min = parseNum(qtyInput.min || 1);
                const max = parseNum(qtyInput.max || 10);
                const current = parseNum(qtyInput.value || min);
                qtyInput.value = Math.min(max, Math.max(min, current + step));
                recalculateSummary();
            });
        });

        document.querySelectorAll('.pkg-qty-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault(); e.stopPropagation();
                const pkgId = btn.dataset.pkg;
                const input = document.getElementById(`extra-dry-${pkgId}`);
                const radio = document.querySelector(`input[name="package_id"][value="${pkgId}"]`);
                if (radio && !radio.checked) {
                    radio.checked = true;
                    document.querySelectorAll('input[name^="extra_dry_qty"]').forEach(inp => {
                        if (inp.id !== `extra-dry-${pkgId}`) inp.value = 0;
                    });
                }
                let val = parseNum(input.value) + parseNum(btn.dataset.step);
                input.value = val < 0 ? 0 : (val > 10 ? 10 : val);
                recalculateSummary();
            });
        });

        packageOptions.forEach((option) => {
            option.addEventListener('change', () => {
                const selectedId = option.value;
                document.querySelectorAll('input[name^="extra_dry_qty"]').forEach(inp => {
                    if (inp.id !== `extra-dry-${selectedId}`) inp.value = 0;
                });
                recalculateSummary();
            });
        });

        addonChecks.forEach((check) => {
            check.addEventListener('change', () => { toggleAddonInput(check); recalculateSummary(); });
            toggleAddonInput(check);
        });

        document.querySelectorAll('.addon-qty').forEach((qtyInput) => {
            qtyInput.addEventListener('click', (event) => event.stopPropagation());
            qtyInput.addEventListener('input', recalculateSummary);
        });

        useCustomerDetergent?.addEventListener('change', () => { enforceChemicalToggles(); recalculateSummary(); });
        useCustomerSoftener?.addEventListener('change', () => { enforceChemicalToggles(); recalculateSummary(); });

        function getLocation() {
            const gpsBtn = document.getElementById('btn-get-location');
            const statusText = document.getElementById('location-status');
            const addressInput = document.getElementById('pickup_address');
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');

            if (navigator.geolocation) {
                statusText.innerText = "กำลังค้นหาพิกัด...";
                gpsBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> กำลังค้นหาพิกัด...';
                gpsBtn.classList.add('opacity-75', 'cursor-not-allowed');

                navigator.geolocation.getCurrentPosition(
                    async function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        
                        latInput.value = lat;
                        lngInput.value = lng;

                        try {
                            const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=th`);
                            const data = await response.json();
                            if (data && data.display_name) {
                                addressInput.value = data.display_name;
                            } else {
                                addressInput.value = `พิกัด GPS: https://maps.google.com/?q=${lat},${lng}`; 
                            }
                        } catch (error) {
                            addressInput.value = `พิกัด GPS: https://maps.google.com/?q=${lat},${lng}`;
                        }

                        gpsBtn.innerHTML = '<i class="fa-solid fa-check text-green-500"></i> ปักหมุดสำเร็จ!';
                        gpsBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                        gpsBtn.classList.replace('text-blue-600', 'text-green-600');
                        gpsBtn.classList.replace('bg-blue-50', 'bg-green-50');
                        gpsBtn.classList.replace('border-blue-200', 'border-green-200');
                        
                        statusText.innerHTML = "ระบบได้รับพิกัด GPS เรียบร้อยแล้ว (คุณสามารถแก้ไขที่อยู่ให้ชัดเจนขึ้นได้)";
                        statusText.classList.replace('text-gray-500', 'text-green-600');
                    }, 
                    function(error) {
                        alert('ไม่สามารถดึงตำแหน่งได้ กรุณาอนุญาตให้เบราว์เซอร์เข้าถึง Location ของคุณครับ');
                        gpsBtn.innerHTML = '<i class="fa-solid fa-location-crosshairs"></i> ดึงตำแหน่งปัจจุบันของฉัน (GPS)';
                        gpsBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                        statusText.innerHTML = "";
                    }
                );
            } else {
                alert("เบราว์เซอร์ของคุณไม่รองรับการปักหมุด GPS ครับ");
            }
        }

        // 🌟 อัปเดตสคริปต์สลับคิวเวลาให้รองรับ วันนี้/พรุ่งนี้
        const timeSlotRadiosList = document.querySelectorAll('.time-slot-radio');
        const queueItemsList = document.querySelectorAll('.queue-item');
        const queueCounterBadge = document.getElementById('queue-counter-badge');
        const emptySlotMessage = document.getElementById('empty-slot-message');
        const dateRadiosList = document.querySelectorAll('.pickup-date-radio');

        function updateQueueDisplay() {
            const checkedTime = document.querySelector('.time-slot-radio:checked');
            const checkedDate = document.querySelector('.pickup-date-radio:checked')?.value || 'today';
            
            if (!checkedTime) {
                let totalForDate = 0;
                queueItemsList.forEach(item => {
                    if (item.dataset.date === checkedDate) {
                        item.style.display = 'flex';
                        totalForDate++;
                    } else {
                        item.style.display = 'none';
                    }
                });
                if (queueCounterBadge) queueCounterBadge.innerHTML = `แสดงทั้งหมด ${totalForDate} คิว`;
                if (emptySlotMessage) emptySlotMessage.classList.add('hidden');
                return;
            }

            const selectedSlotId = checkedTime.value;
            const slotName = checkedTime.closest('label').querySelector('.slot-name-text').innerText;
            let visibleCount = 0;

            queueItemsList.forEach(item => {
                if (item.dataset.slotId === selectedSlotId && item.dataset.date === checkedDate) { 
                    item.style.display = 'flex'; 
                    visibleCount++; 
                } else { 
                    item.style.display = 'none'; 
                }
            });

            if (queueCounterBadge) queueCounterBadge.innerHTML = `<i class="fa-solid fa-filter"></i> ${slotName}: ${visibleCount} คิว`;
            
            if (emptySlotMessage) {
                // หาดูว่าวันนั้นมีคิวรวมทั้งหมดกี่คิว
                let totalForDate = 0;
                queueItemsList.forEach(item => { if(item.dataset.date === checkedDate) totalForDate++; });

                if (visibleCount === 0 && totalForDate > 0) emptySlotMessage.classList.remove('hidden');
                else emptySlotMessage.classList.add('hidden');
            }
        }

        timeSlotRadiosList.forEach(radio => radio.addEventListener('change', updateQueueDisplay));
        dateRadiosList.forEach(radio => radio.addEventListener('change', updateQueueDisplay));

        const pkgFilterBtns = document.querySelectorAll('.pkg-filter-btn');
        const packageCards = document.querySelectorAll('.package-card-wrapper');
        pkgFilterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                pkgFilterBtns.forEach(b => {
                    b.classList.remove('bg-pink-500', 'text-white', 'border-transparent', 'shadow-sm');
                    b.classList.add('bg-white', 'text-gray-600', 'border-gray-200');
                });
                btn.classList.remove('bg-white', 'text-gray-600', 'border-gray-200');
                btn.classList.add('bg-pink-500', 'text-white', 'border-transparent', 'shadow-sm');
                const filterValue = btn.dataset.filter;
                packageCards.forEach(card => {
                    const name = card.dataset.name;
                    if (filterValue === 'all') card.style.display = 'block';
                    else card.style.display = name.includes(filterValue) ? 'block' : 'none';
                });
            });
        });

        // Initialize
        enforceChemicalToggles();
        recalculateSummary();
        updateQueueDisplay();
    </script>
@endsection