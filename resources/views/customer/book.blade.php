@extends('layouts.customer')

@section('title', 'จองคิวรับผ้า - Laundry Drop')

@section('content')
    <div class="max-w-6xl mx-auto pb-12 px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-10 pt-8">
            <div
                class="inline-flex items-center justify-center bg-pink-100 dark:bg-slate-700 text-pink-500 dark:text-pink-400 w-20 h-20 rounded-full mb-5 shadow-sm">
                <i class="fa-solid fa-motorcycle text-4xl"></i>
            </div>
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-gray-100 mb-3 tracking-tight">
                จองคิวให้ไรเดอร์ไปรับผ้า</h2>
            <p class="text-gray-500 dark:text-gray-400 text-lg">เลือกแพ็กเกจ, เมนูเสริม, และวิธีชำระเงินได้จบในที่เดียว</p>
        </div>

        <div
            class="bg-white dark:bg-slate-800 rounded-3xl p-5 md:p-8 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700 transition-colors relative z-10">
            <form action="{{ route('customer.book.store') }}" method="POST"
                class="lg:grid lg:grid-cols-12 lg:gap-8 pb-24 lg:pb-0" id="booking-form">
                @csrf

                @if ($errors->any())
                    <div
                        class="lg:col-span-12 mb-6 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800 rounded-2xl p-5 shadow-sm">
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

                    <div class="rounded-3xl border border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 md:p-8 shadow-sm">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="shrink-0 bg-gradient-to-br from-pink-400 to-pink-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold shadow-md text-lg">
                                1
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 tracking-tight">
                                เลือกขนาดแพ็กเกจ
                            </h3>
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

                                            <h4 class="font-bold text-lg text-gray-800 dark:text-gray-100 mb-2">
                                                {{ $package->name }}
                                            </h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                                                {{ $package->description }}
                                            </p>
                                        </div>
                                        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-slate-600">
                                            <p class="text-2xl font-black text-pink-600 dark:text-pink-400">
                                                ฿{{ number_format($package->price) }}
                                            </p>

                                            <div class="mt-3 pt-3 border-t border-dashed border-gray-200 dark:border-slate-600 flex items-center justify-between">
                                                <p class="text-[11px] font-bold text-gray-500 dark:text-gray-400">
                                                    เวลาอบเพิ่ม <span class="text-pink-500">+10฿</span>
                                                </p>
                                                <div class="flex items-center border border-gray-200 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-800 z-20 relative">
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

                    <div
                        class="rounded-3xl border border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 md:p-8 shadow-sm">
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="shrink-0 bg-gradient-to-br from-pink-400 to-pink-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold shadow-md text-lg">
                                2</div>
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 tracking-tight">
                                เลือกน้ำยาและบริการเสริม</h3>
                        </div>

                        <div class="pl-0 md:pl-14 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <label
                                    class="flex items-start gap-3 p-4 rounded-2xl border-2 border-transparent bg-gray-50 dark:bg-slate-700/50 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors cursor-pointer ring-1 ring-gray-200 dark:ring-slate-600 focus-within:ring-pink-400">
                                    <input type="checkbox" id="use_customer_detergent" name="use_customer_detergent"
                                        value="1"
                                        class="w-5 h-5 rounded border-gray-300 text-pink-500 focus:ring-pink-500 dark:border-slate-500 dark:bg-slate-800 mt-0.5"
                                        {{ old('use_customer_detergent') ? 'checked' : '' }}>
                                    <div class="flex-1">
                                        <p class="font-bold text-gray-800 dark:text-gray-100 text-sm">ลูกค้ามีน้ำยาซักผ้าเอง
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">ปิดตัวเลือกหมวดน้ำยาซัก
                                            และไม่คิดค่าส่วนนี้</p>
                                    </div>
                                </label>

                                <label
                                    class="flex items-start gap-3 p-4 rounded-2xl border-2 border-transparent bg-gray-50 dark:bg-slate-700/50 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors cursor-pointer ring-1 ring-gray-200 dark:ring-slate-600 focus-within:ring-pink-400">
                                    <input type="checkbox" id="use_customer_softener" name="use_customer_softener"
                                        value="1"
                                        class="w-5 h-5 rounded border-gray-300 text-pink-500 focus:ring-pink-500 dark:border-slate-500 dark:bg-slate-800 mt-0.5"
                                        {{ old('use_customer_softener') ? 'checked' : '' }}>
                                    <div class="flex-1">
                                        <p class="font-bold text-gray-800 dark:text-gray-100 text-sm">
                                            ลูกค้ามีน้ำยาปรับผ้านุ่มเอง</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            ปิดตัวเลือกหมวดน้ำยาปรับผ้านุ่ม และไม่คิดค่าส่วนนี้</p>
                                    </div>
                                </label>
                            </div>

                            <div
                                class="rounded-2xl border border-blue-200 bg-blue-50/80 dark:bg-blue-900/20 dark:border-blue-800 p-4 text-sm text-blue-800 dark:text-blue-200 flex gap-3">
                                <i class="fa-solid fa-circle-info text-blue-500 dark:text-blue-400 mt-0.5 text-lg"></i>
                                <div>
                                    <p class="font-bold text-base mb-1">กรณีไม่มีน้ำยามาเอง</p>
                                    <p class="opacity-90 leading-relaxed">
                                        ระบบจะเลือกค่าเริ่มต้นอัตโนมัติแยกตามหมวดที่ลูกค้าไม่ได้ติ๊กว่ามีมาเอง</p>
                                    <ul class="mt-2 space-y-1 list-disc list-inside opacity-90">
                                        <li>สูตรน้ำยาซัก: <span id="default-addon-hint"
                                                class="font-semibold underline decoration-blue-300 underline-offset-2">ยังไม่ได้เลือกแพ็กเกจ</span>
                                        </li>
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
                                                data-category="{{ $addon['category'] }}"
                                                data-addon-price="{{ (float) $addon['price'] }}">
                                                <div class="flex items-start gap-3 w-full">
                                                    <div class="shrink-0 pt-1">
                                                        <input type="checkbox" name="addons[{{ $addonKey }}][code]"
                                                            value="{{ $addon['code'] }}"
                                                            class="addon-check w-5 h-5 rounded border-gray-300 text-pink-500 focus:ring-pink-500 transition-colors cursor-pointer"
                                                            {{ $checked ? 'checked' : '' }}>
                                                    </div>
                                                    <div class="shrink-0">
                                                        @if (!empty($addon['image_path']))
                                                            <img src="{{ asset('storage/' . $addon['image_path']) }}"
                                                                alt="{{ $addon['name'] }}"
                                                                class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border border-gray-100 dark:border-slate-600 shadow-sm">
                                                        @else
                                                            <div
                                                                class="w-16 h-16 md:w-20 md:h-20 rounded-xl border border-gray-100 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 flex items-center justify-center shadow-sm">
                                                                <i
                                                                    class="fa-solid fa-box text-gray-300 dark:text-gray-500 text-xl md:text-2xl"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0 break-words">
                                                        <p
                                                            class="font-bold text-gray-800 dark:text-gray-100 text-sm md:text-base leading-snug mb-1">
                                                            {{ $addon['name'] }}</p>
                                                        <p class="text-sm font-semibold text-pink-500 dark:text-pink-400">+
                                                            ฿{{ number_format($addon['price']) }} <span
                                                                class="text-xs text-gray-500 dark:text-gray-400 font-normal">/หน่วย</span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="flex justify-end items-end mt-auto pt-2">
                                                    <div class="flex items-center border-2 border-gray-100 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 overflow-hidden shadow-sm"
                                                        onclick="event.stopPropagation()">
                                                        <button type="button"
                                                            class="qty-step w-9 h-9 md:w-10 md:h-10 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors flex items-center justify-center"
                                                            data-step="-1"><i
                                                                class="fa-solid fa-minus text-xs"></i></button>
                                                        <input type="number" min="1" max="10"
                                                            name="addons[{{ $addonKey }}][qty]"
                                                            value="{{ $oldQty }}"
                                                            class="addon-qty w-10 md:w-12 h-9 md:h-10 p-0 text-center border-x border-y-0 border-gray-100 dark:border-slate-600 bg-gray-50 dark:bg-slate-800 font-bold text-gray-800 dark:text-gray-100 focus:ring-0 text-sm md:text-base"
                                                            {{ $checked ? '' : 'disabled' }}>
                                                        <button type="button"
                                                            class="qty-step w-9 h-9 md:w-10 md:h-10 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors flex items-center justify-center"
                                                            data-step="1"><i
                                                                class="fa-solid fa-plus text-xs"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-gray-100 dark:border-slate-700">
                                    <div class="flex items-center gap-2 mb-4">
                                        <i class="fa-solid fa-bottle-droplet text-xl text-pink-400"></i>
                                        <h4 class="text-lg font-bold text-gray-800 dark:text-gray-100">น้ำยาปรับผ้านุ่ม
                                        </h4>
                                    </div>
                                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4" id="softener-wrapper">
                                        @foreach ($softenerAddons as $addon)
                                            @php
                                                $addonKey = $addon['code'];
                                                $oldQty = old('addons.' . $addonKey . '.qty', 1);
                                                $checked = old('addons.' . $addonKey . '.code') === $addon['code'];
                                            @endphp
                                            <div class="addon-card relative p-4 rounded-2xl border-2 border-gray-100 dark:border-slate-600 bg-white dark:bg-slate-800 hover:border-pink-300 dark:hover:border-pink-500 transition-all duration-300 cursor-pointer shadow-sm hover:shadow-md flex flex-col h-full justify-between gap-4"
                                                data-category="{{ $addon['category'] }}"
                                                data-addon-price="{{ (float) $addon['price'] }}">
                                                <div class="flex items-start gap-3 w-full">
                                                    <div class="shrink-0 pt-1">
                                                        <input type="checkbox" name="addons[{{ $addonKey }}][code]"
                                                            value="{{ $addon['code'] }}"
                                                            class="addon-check w-5 h-5 rounded border-gray-300 text-pink-500 focus:ring-pink-500 transition-colors cursor-pointer"
                                                            {{ $checked ? 'checked' : '' }}>
                                                    </div>
                                                    <div class="shrink-0">
                                                        @if (!empty($addon['image_path']))
                                                            <img src="{{ asset('storage/' . $addon['image_path']) }}"
                                                                alt="{{ $addon['name'] }}"
                                                                class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border border-gray-100 dark:border-slate-600 shadow-sm">
                                                        @else
                                                            <div
                                                                class="w-16 h-16 md:w-20 md:h-20 rounded-xl border border-gray-100 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 flex items-center justify-center shadow-sm">
                                                                <i
                                                                    class="fa-solid fa-box text-gray-300 dark:text-gray-500 text-xl md:text-2xl"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0 break-words">
                                                        <p
                                                            class="font-bold text-gray-800 dark:text-gray-100 text-sm md:text-base leading-snug mb-1">
                                                            {{ $addon['name'] }}</p>
                                                        <p class="text-sm font-semibold text-pink-500 dark:text-pink-400">+
                                                            ฿{{ number_format($addon['price']) }} <span
                                                                class="text-xs text-gray-500 dark:text-gray-400 font-normal">/หน่วย</span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="flex justify-end items-end mt-auto pt-2">
                                                    <div class="flex items-center border-2 border-gray-100 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 overflow-hidden shadow-sm"
                                                        onclick="event.stopPropagation()">
                                                        <button type="button"
                                                            class="qty-step w-9 h-9 md:w-10 md:h-10 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors flex items-center justify-center"
                                                            data-step="-1"><i
                                                                class="fa-solid fa-minus text-xs"></i></button>
                                                        <input type="number" min="1" max="10"
                                                            name="addons[{{ $addonKey }}][qty]"
                                                            value="{{ $oldQty }}"
                                                            class="addon-qty w-10 md:w-12 h-9 md:h-10 p-0 text-center border-x border-y-0 border-gray-100 dark:border-slate-600 bg-gray-50 dark:bg-slate-800 font-bold text-gray-800 dark:text-gray-100 focus:ring-0 text-sm md:text-base"
                                                            {{ $checked ? '' : 'disabled' }}>
                                                        <button type="button"
                                                            class="qty-step w-9 h-9 md:w-10 md:h-10 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors flex items-center justify-center"
                                                            data-step="1"><i
                                                                class="fa-solid fa-plus text-xs"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
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
                                            data-category="{{ $addon['category'] }}"
                                            data-addon-price="{{ (float) $addon['price'] }}">
                                            <div class="flex items-start gap-3 w-full">
                                                <div class="shrink-0 pt-1">
                                                    <input type="checkbox" name="addons[{{ $addonKey }}][code]"
                                                        value="{{ $addon['code'] }}"
                                                        class="addon-check w-5 h-5 rounded border-gray-300 text-pink-500 focus:ring-pink-500 transition-colors cursor-pointer"
                                                        {{ $checked ? 'checked' : '' }}>
                                                </div>
                                                <div class="shrink-0">
                                                    @if (!empty($addon['image_path']))
                                                        <img src="{{ asset('storage/' . $addon['image_path']) }}"
                                                            alt="{{ $addon['name'] }}"
                                                            class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border border-gray-100 dark:border-slate-600 shadow-sm">
                                                    @else
                                                        <div
                                                            class="w-16 h-16 md:w-20 md:h-20 rounded-xl border border-gray-100 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 flex items-center justify-center shadow-sm">
                                                            <i
                                                                class="fa-solid fa-box text-gray-300 dark:text-gray-500 text-xl md:text-2xl"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0 break-words">
                                                    <p
                                                        class="font-bold text-gray-800 dark:text-gray-100 text-sm md:text-base leading-snug mb-1">
                                                        {{ $addon['name'] }}</p>
                                                    <p class="text-sm font-semibold text-pink-500 dark:text-pink-400">+
                                                        ฿{{ number_format($addon['price']) }} <span
                                                            class="text-xs text-gray-500 dark:text-gray-400 font-normal">/หน่วย</span>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex justify-end items-end mt-auto pt-2">
                                                <div class="flex items-center border-2 border-gray-100 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 overflow-hidden shadow-sm"
                                                    onclick="event.stopPropagation()">
                                                    <button type="button"
                                                        class="qty-step w-9 h-9 md:w-10 md:h-10 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors flex items-center justify-center"
                                                        data-step="-1"><i class="fa-solid fa-minus text-xs"></i></button>
                                                    <input type="number" min="1" max="10"
                                                        name="addons[{{ $addonKey }}][qty]"
                                                        value="{{ $oldQty }}"
                                                        class="addon-qty w-10 md:w-12 h-9 md:h-10 p-0 text-center border-x border-y-0 border-gray-100 dark:border-slate-600 bg-gray-50 dark:bg-slate-800 font-bold text-gray-800 dark:text-gray-100 focus:ring-0 text-sm md:text-base"
                                                        {{ $checked ? '' : 'disabled' }}>
                                                    <button type="button"
                                                        class="qty-step w-9 h-9 md:w-10 md:h-10 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors flex items-center justify-center"
                                                        data-step="1"><i class="fa-solid fa-plus text-xs"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 md:p-8 shadow-sm">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="shrink-0 bg-gradient-to-br from-pink-400 to-pink-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold shadow-md text-lg">
                                3</div>
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 tracking-tight">เลือกรอบเวลารับผ้า</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 pl-0 md:pl-14">
                            @foreach ($timeSlots as $slot)
                                <label class="cursor-pointer relative group">
                                    <input type="radio" name="time_slot_id" value="{{ $slot->id }}" class="peer sr-only time-slot-radio" required {{ old('time_slot_id') == $slot->id ? 'checked' : '' }}>
                                    <div class="px-5 py-4 rounded-2xl border-2 border-gray-100 dark:border-slate-600 bg-gray-50/50 dark:bg-slate-700/30 hover:border-pink-300 peer-checked:border-pink-500 peer-checked:bg-pink-50 dark:peer-checked:bg-slate-700 transition-all duration-200 flex items-center justify-between group-hover:shadow-sm">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-white dark:bg-slate-800 flex items-center justify-center shadow-sm text-gray-400 peer-checked:text-pink-500 transition-colors">
                                                <i class="fa-regular fa-clock"></i>
                                            </div>
                                            <span class="font-bold text-gray-700 dark:text-gray-200 slot-name-text">{{ $slot->round_name }}</span>
                                        </div>
                                        <i class="fa-solid fa-check-circle text-xl text-pink-500 scale-0 opacity-0 peer-checked:scale-100 peer-checked:opacity-100 transition-all duration-300"></i>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        @if(isset($todayQueues))
                        <div class="pl-0 md:pl-14 mt-8 pt-6 border-t border-dashed border-gray-200 dark:border-slate-700">
                            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                                <h4 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                                    <i class="fa-solid fa-users text-pink-500"></i> สถานะคิววันนี้
                                </h4>
                                <span id="queue-counter-badge" class="text-sm font-semibold bg-pink-100 text-pink-700 dark:bg-slate-700 dark:text-pink-400 px-3 py-1.5 rounded-full shadow-sm">
                                    แสดงทั้งหมด {{ $todayQueues->count() }} คิว
                                </span>
                            </div>

                            @if($todayQueues->isEmpty())
                                <div class="text-center py-5 bg-gray-50 dark:bg-slate-700/30 rounded-2xl border border-dashed border-gray-200 dark:border-slate-600">
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">ยังไม่มีคิวในวันนี้ 🎉 คุณสามารถจองเป็นคิวแรกได้เลย!</p>
                                </div>
                            @else
                                <div id="queue-list-container" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach($todayQueues as $index => $queue)
                                        <div class="queue-item flex items-center gap-3 p-3 rounded-2xl border transition-colors {{ $queue->user_id == Auth::id() ? 'border-pink-300 bg-pink-50/50 dark:bg-slate-700/80 dark:border-pink-500/50' : 'border-gray-100 dark:border-slate-600 bg-gray-50 dark:bg-slate-700/30' }}" 
                                             data-slot-id="{{ $queue->time_slot_id }}">
                                            
                                            <div class="shrink-0 w-8 h-8 {{ $queue->user_id == Auth::id() ? 'bg-pink-500 text-white' : 'bg-white dark:bg-slate-800 text-gray-500 dark:text-gray-300' }} rounded-full flex items-center justify-center font-bold text-xs shadow-sm border border-gray-200 dark:border-slate-600">
                                                {{ $index + 1 }}
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
                                                    'pending' => 'รอรับ',
                                                    'picked_up' => 'รับแล้ว',
                                                    'processing' => 'ซัก/อบ',
                                                    'delivering' => 'กำลังส่ง',
                                                    'completed' => 'เสร็จ',
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
                        @endif
                    </div>

                    <div
                        class="rounded-3xl border border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 md:p-8 shadow-sm">
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="shrink-0 bg-gradient-to-br from-pink-400 to-pink-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold shadow-md text-lg">
                                4</div>
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 tracking-tight">วิธีชำระเงิน
                            </h3>
                        </div>

                        <div class="pl-0 md:pl-14 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="cursor-pointer group">
                                <input type="radio" name="payment_method" value="transfer" class="sr-only peer"
                                    {{ old('payment_method', 'transfer') === 'transfer' ? 'checked' : '' }}>
                                <div
                                    class="rounded-2xl border-2 border-gray-100 dark:border-slate-600 p-5 bg-gray-50/50 dark:bg-slate-700/30 peer-checked:border-pink-500 peer-checked:bg-pink-50/50 dark:peer-checked:bg-slate-700 hover:border-pink-300 transition-all duration-200 flex items-center justify-between group-hover:shadow-sm">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 bg-white dark:bg-slate-800 rounded-xl flex items-center justify-center shadow-sm text-blue-500">
                                            <i class="fa-solid fa-qrcode text-2xl"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-800 dark:text-gray-100">โอนเงิน / สแกน QR</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">แนบสลิปในระบบ</p>
                                        </div>
                                    </div>
                                    <i
                                        class="fa-solid fa-circle-check text-2xl text-pink-500 scale-0 opacity-0 peer-checked:scale-100 peer-checked:opacity-100 transition-all duration-300"></i>
                                </div>
                            </label>

                            <label class="cursor-pointer group">
                                <input type="radio" name="payment_method" value="cash" class="sr-only peer"
                                    {{ old('payment_method') === 'cash' ? 'checked' : '' }}>
                                <div
                                    class="rounded-2xl border-2 border-gray-100 dark:border-slate-600 p-5 bg-gray-50/50 dark:bg-slate-700/30 peer-checked:border-pink-500 peer-checked:bg-pink-50/50 dark:peer-checked:bg-slate-700 hover:border-pink-300 transition-all duration-200 flex items-center justify-between group-hover:shadow-sm">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 bg-white dark:bg-slate-800 rounded-xl flex items-center justify-center shadow-sm text-green-500">
                                            <i class="fa-solid fa-money-bill-wave text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-800 dark:text-gray-100">เงินสดปลายทาง</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">จ่ายกับไรเดอร์</p>
                                        </div>
                                    </div>
                                    <i
                                        class="fa-solid fa-circle-check text-2xl text-pink-500 scale-0 opacity-0 peer-checked:scale-100 peer-checked:opacity-100 transition-all duration-300"></i>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div
                        class="rounded-3xl border border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 md:p-8 shadow-sm">
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="shrink-0 bg-gradient-to-br from-pink-400 to-pink-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold shadow-md text-lg">
                                5</div>
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 tracking-tight">
                                สถานที่รับ-ส่งผ้า</h3>
                        </div>

                        <div class="pl-0 md:pl-14">
                            <label
                                class="block font-bold text-gray-700 dark:text-gray-300 mb-2">ที่อยู่ปัจจุบันสำหรับรับ-ส่งผ้า</label>

                            <button type="button" onclick="getLocation()" id="btn-get-location"
                                class="mb-3 flex items-center gap-2 bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-100 px-4 py-2 rounded-xl transition-colors text-sm font-bold">
                                <i class="fa-solid fa-location-crosshairs"></i> ดึงตำแหน่งปัจจุบันของฉัน (GPS)
                            </button>

                            <textarea id="pickup_address" name="pickup_address" rows="3" required readonly
                                class="w-full p-5 rounded-2xl border-2 border-gray-100 dark:border-slate-600 bg-gray-50 dark:bg-slate-700/50 text-gray-800 dark:text-gray-100 focus:border-pink-500 focus:ring-4 focus:ring-pink-500/10 outline-none transition-all resize-none leading-relaxed shadow-inner">{{ old('pickup_address', Auth::user()->address) }}</textarea>

                            <input type="hidden" id="latitude" name="latitude"
                                value="{{ old('latitude', Auth::user()->latitude) }}">
                            <input type="hidden" id="longitude" name="longitude"
                                value="{{ old('longitude', Auth::user()->longitude) }}">

                            <p id="location-status" class="mt-2 text-sm text-gray-500 font-medium"></p>
                        </div>
                    </div>
                </div>

                <aside class="lg:col-span-4 mt-8 lg:mt-0 relative">
                    <div class="lg:sticky lg:top-24 space-y-5">
                        <div
                            class="rounded-3xl bg-white dark:bg-slate-800 border-2 border-pink-100 dark:border-slate-700 shadow-xl shadow-pink-100/50 dark:shadow-none overflow-hidden">
                            <div
                                class="bg-pink-50 dark:bg-slate-700/50 px-6 py-4 border-b border-pink-100 dark:border-slate-600">
                                <h4 class="font-bold text-lg text-pink-600 dark:text-pink-400 flex items-center gap-2">
                                    <i class="fa-solid fa-receipt"></i> สรุปรายการจอง
                                </h4>
                            </div>
                            <div class="p-6 space-y-4 text-base">
                                <div class="flex justify-between items-center text-gray-600 dark:text-gray-300">
                                    <span>ราคาแพ็กเกจ</span>
                                    <span id="summary-subtotal"
                                        class="font-medium text-gray-800 dark:text-gray-100">฿0</span>
                                </div>
                                <div class="flex justify-between items-center text-gray-600 dark:text-gray-300">
                                    <span>เมนูเสริม</span>
                                    <span id="summary-addon"
                                        class="font-medium text-gray-800 dark:text-gray-100">฿0</span>
                                </div>
                                <div
                                    class="pt-4 mt-2 border-t border-dashed border-gray-200 dark:border-slate-600 flex justify-between items-end">
                                    <span class="font-bold text-gray-700 dark:text-gray-200 pb-1">ยอดชำระทั้งหมด</span>
                                    <span id="summary-total"
                                        class="text-3xl font-black text-pink-600 dark:text-pink-400">฿0</span>
                                </div>
                            </div>
                        </div>

                        <div
                            class="rounded-2xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 p-4 text-sm text-amber-800 dark:text-amber-200 flex gap-3 shadow-sm">
                            <i class="fa-solid fa-shield-check text-amber-500 mt-0.5 text-lg"></i>
                            <p class="leading-relaxed">โปรดตรวจสอบแพ็กเกจ รอบเวลา
                                และที่อยู่ให้ถูกต้องครบถ้วนก่อนกดยืนยันการจอง</p>
                        </div>

                        <button type="submit"
                            class="hidden lg:flex w-full bg-gradient-to-r from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 text-white text-xl font-bold py-5 rounded-2xl shadow-lg shadow-pink-500/30 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 justify-center items-center gap-3">
                            ยืนยันการจองคิว <i class="fa-solid fa-arrow-right-long text-xl"></i>
                        </button>
                    </div>
                </aside>

                <div
                    class="fixed bottom-0 left-0 right-0 z-50 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border-t border-gray-200 dark:border-slate-700 p-4 lg:hidden shadow-[0_-10px_15px_-3px_rgba(0,0,0,0.05)]">
                    <div class="max-w-6xl mx-auto flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">ยอดชำระสุทธิ</p>
                            <p id="summary-total-mobile"
                                class="text-2xl font-black text-pink-600 dark:text-pink-400 leading-none mt-1">฿0</p>
                        </div>
                        <button type="submit"
                            class="bg-gradient-to-r from-pink-500 to-pink-600 text-white font-bold text-lg px-8 py-3.5 rounded-2xl shadow-lg shadow-pink-500/30 active:scale-95 transition-all w-1/2 flex items-center justify-center gap-2">
                            จองคิวเลย
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        .addon-qty::-webkit-outer-spin-button,
        .addon-qty::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .addon-qty {
            -moz-appearance: textfield;
            appearance: textfield;
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* 🌟 Custom Scrollbar (สวยงามทั้ง Light/Dark Mode) */
        .custom-scrollbar::-webkit-scrollbar {
            height: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
            cursor: pointer;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #f472b6;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-track {
            background: #1e293b;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #475569;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #f472b6;
        }
    </style>

    <script>
        const packageOptions = Array.from(document.querySelectorAll('.package-option'));
        const addonChecks = Array.from(document.querySelectorAll('.addon-check'));
        const useCustomerDetergent = document.getElementById('use_customer_detergent');
        const useCustomerSoftener = document.getElementById('use_customer_softener');

        const subtotalEl = document.getElementById('summary-subtotal');
        const addonEl = document.getElementById('summary-addon');
        const deliveryEl = document.getElementById('summary-delivery');
        const totalEl = document.getElementById('summary-total');
        const totalMobileEl = document.getElementById('summary-total-mobile');
        const distanceNoteEl = document.getElementById('summary-distance-note');
        let currentDeliveryFee = 0;

        // 🌟 ระบบ Filter คิวแบบ Dynamic
        const timeSlotRadiosList = document.querySelectorAll('.time-slot-radio');
        const queueItemsList = document.querySelectorAll('.queue-item');
        const queueCounterBadge = document.getElementById('queue-counter-badge');
        const emptySlotMessage = document.getElementById('empty-slot-message');
        const totalQueuesForToday = {{ isset($todayQueues) ? $todayQueues->count() : 0 }};

        function updateQueueDisplay() {
            const checkedRadio = document.querySelector('.time-slot-radio:checked');

            if (!checkedRadio) {
                queueItemsList.forEach(item => item.style.display = 'flex');
                if (queueCounterBadge) {
                    queueCounterBadge.innerHTML = `แสดงทั้งหมด ${totalQueuesForToday} คิว`;
                }
                if (emptySlotMessage) emptySlotMessage.classList.add('hidden');
                return;
            }

            const selectedSlotId = checkedRadio.value;
            const slotName = checkedRadio.closest('label').querySelector('.slot-name-text').innerText;
            let visibleCount = 0;

            queueItemsList.forEach(item => {
                if (item.dataset.slotId === selectedSlotId) {
                    item.style.display = 'flex';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            if (queueCounterBadge) {
                queueCounterBadge.innerHTML =
                    `<i class="fa-solid fa-filter"></i> ${slotName}: ${visibleCount} คิว (รวมทั้งวัน ${totalQueuesForToday})`;
            }

            if (emptySlotMessage) {
                if (visibleCount === 0 && totalQueuesForToday > 0) {
                    emptySlotMessage.classList.remove('hidden');
                } else {
                    emptySlotMessage.classList.add('hidden');
                }
            }
        }

        timeSlotRadiosList.forEach(radio => {
            radio.addEventListener('change', updateQueueDisplay);
        });

        document.addEventListener('DOMContentLoaded', updateQueueDisplay);

        function parseNum(value) {
            const number = Number(value);
            return Number.isFinite(number) ? number : 0;
        }

        function formatBaht(value) {
            return `฿${parseNum(value).toLocaleString('th-TH')}`;
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
                    check.checked = false;
                    check.disabled = true;
                    qty.disabled = true;
                    card.classList.add('opacity-50', 'pointer-events-none');
                    card.classList.remove('border-pink-400', 'bg-pink-50/30');
                } else {
                    check.disabled = false;
                    card.classList.remove('opacity-50', 'pointer-events-none');
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

        function recalculateSummary() {
            const selectedPackage = packageOptions.find((option) => option.checked);
            const subtotal = selectedPackage ? parseNum(selectedPackage.dataset.packagePrice) : 0;
            const defaultAddonHint = document.getElementById('default-addon-hint');

            if (defaultAddonHint) {
                defaultAddonHint.textContent = selectedPackage ?
                    (selectedPackage.dataset.defaultAddonName || 'ใช้สูตรเริ่มต้นของระบบ') :
                    'ยังไม่ได้เลือกแพ็กเกจ';
            }

            let addonTotal = 0;
            addonChecks.forEach((check) => {
                if (!check.checked || check.disabled) return;

                const card = check.closest('.addon-card');
                const qtyInput = card?.querySelector('.addon-qty');
                const price = parseNum(card?.dataset.addonPrice);
                const qty = parseNum(qtyInput?.value || 1);

                addonTotal += price * Math.max(1, qty);
            });

            // 🌟 เพิ่มคำนวณเงินค่า "อบผ้าเพิ่ม" 
            let extraDryTotal = 0;
            if (selectedPackage) {
                const pkgId = selectedPackage.value;
                const extraQtyInput = document.getElementById(`extra-dry-${pkgId}`);
                if (extraQtyInput) {
                    extraDryTotal = parseNum(extraQtyInput.value) * 10; // ครั้งละ 10 บาท
                }
            }

            // รวมราคาทั้งหมด
            const grandTotal = subtotal + addonTotal + extraDryTotal + currentDeliveryFee;

            subtotalEl.textContent = formatBaht(subtotal);
            // แสดงราคาเมนูเสริมรวมกับค่าอบผ้าไปเลย
            addonEl.textContent = formatBaht(addonTotal + extraDryTotal);
            if (deliveryEl) deliveryEl.textContent = formatBaht(currentDeliveryFee);
            totalEl.textContent = formatBaht(grandTotal);
            if (totalMobileEl) totalMobileEl.textContent = formatBaht(grandTotal);
        }

        async function refreshDeliveryQuote() {
            const latitude = document.getElementById('latitude')?.value;
            const longitude = document.getElementById('longitude')?.value;

            if (!latitude || !longitude) {
                currentDeliveryFee = 0;
                if (distanceNoteEl) {
                    distanceNoteEl.textContent = 'แชร์ GPS เพื่อคำนวณระยะทางจากร้าน';
                    distanceNoteEl.className = 'text-xs text-gray-400 dark:text-gray-500';
                }
                recalculateSummary();
                return;
            }

            try {
                const url = new URL(@json(route('customer.book.delivery_quote')));
                url.searchParams.set('latitude', latitude);
                url.searchParams.set('longitude', longitude);

                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                });

                if (!response.ok) {
                    throw new Error('delivery-quote-request-failed');
                }

                const quote = await response.json();
                currentDeliveryFee = Number(quote.fee || 0);

                if (distanceNoteEl) {
                    if (!quote.shop_configured) {
                        distanceNoteEl.textContent = 'ยังไม่ได้ตั้งค่าพิกัดร้านในระบบ';
                        distanceNoteEl.className = 'text-xs text-amber-500 dark:text-amber-400';
                    } else if (!quote.has_customer_coordinates) {
                        distanceNoteEl.textContent = 'แชร์ GPS เพื่อคำนวณระยะทางจากร้าน';
                        distanceNoteEl.className = 'text-xs text-gray-400 dark:text-gray-500';
                    } else {
                        const distanceText = Number(quote.driving_distance_km || 0).toFixed(2);
                        const sourceText = quote.source === 'osrm' ? 'ขับรถจริง' : 'คำนวณสำรอง';
                        distanceNoteEl.textContent = `ระยะทางจากร้าน ${distanceText} กม. (${sourceText})`;
                        distanceNoteEl.className = 'text-xs text-blue-500 dark:text-blue-300';
                    }
                }

                recalculateSummary();
            } catch (error) {
                currentDeliveryFee = 0;
                if (distanceNoteEl) {
                    distanceNoteEl.textContent = 'คำนวณระยะทางไม่สำเร็จ ระบบจะตรวจสอบอีกครั้งตอนบันทึกออเดอร์';
                    distanceNoteEl.className = 'text-xs text-amber-500 dark:text-amber-400';
                }
                recalculateSummary();
            }
        }

        document.querySelectorAll('.addon-card').forEach((card) => {
            card.addEventListener('click', (event) => {
                if (event.target.closest('.addon-qty') || event.target.closest('.qty-step') || event.target
                    .closest('.addon-check')) return;
                const check = card.querySelector('.addon-check');
                if (!check || check.disabled) return;
                check.checked = !check.checked;
                toggleAddonInput(check);
                recalculateSummary();
            });
        });

        document.querySelectorAll('.qty-step').forEach((btn) => {
            btn.addEventListener('click', (event) => {
                event.preventDefault();
                event.stopPropagation();

                const card = btn.closest('.addon-card');
                const qtyInput = card?.querySelector('.addon-qty');
                const check = card?.querySelector('.addon-check');
                if (!qtyInput || !check || check.disabled) return;

                if (!check.checked) {
                    check.checked = true;
                    toggleAddonInput(check);
                }

                const step = parseNum(btn.dataset.step || 1);
                const min = parseNum(qtyInput.min || 1);
                const max = parseNum(qtyInput.max || 10);
                const current = parseNum(qtyInput.value || min);
                const next = Math.min(max, Math.max(min, current + step));

                qtyInput.value = next;
                recalculateSummary();
            });
        });

        // 🌟 ระบบกดปุ่ม +/- เพิ่มเวลาอบผ้า
        document.querySelectorAll('.pkg-qty-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation(); // ป้องกันไม่ให้คลิกแล้วไปทับซ้อนกับการเลือกแพ็กเกจ

                const pkgId = btn.dataset.pkg;
                const input = document.getElementById(`extra-dry-${pkgId}`);
                const radio = document.querySelector(`input[name="package_id"][value="${pkgId}"]`);

                // ถ้ากดเพิ่มเวลา ให้ระบบเลือกแพ็กเกจนี้ให้อัตโนมัติ (ถ้ายังไม่ได้เลือก)
                if (radio && !radio.checked) {
                    radio.checked = true;
                    // รีเซ็ตเวลาอบของแพ็กเกจอื่นๆ ให้เป็น 0 ให้หมด
                    document.querySelectorAll('input[name^="extra_dry_qty"]').forEach(inp => {
                        if (inp.id !== `extra-dry-${pkgId}`) inp.value = 0;
                    });
                }

                const step = parseNum(btn.dataset.step);
                let val = parseNum(input.value) + step;
                if (val < 0) val = 0;
                if (val > 10) val = 10; // จำกัดให้เพิ่มได้สูงสุด 10 ครั้ง (100 บาท)
                input.value = val;

                recalculateSummary();
            });
        });

        // 🌟 ถ้าระหว่างนั้นลูกค้าเปลี่ยนใจไปคลิกแพ็กเกจอื่น ให้รีเซ็ตเวลาอบกลับเป็น 0
        packageOptions.forEach((option) => {
            option.addEventListener('change', () => {
                const selectedId = option.value;
                document.querySelectorAll('input[name^="extra_dry_qty"]').forEach(inp => {
                    if (inp.id !== `extra-dry-${selectedId}`) {
                        inp.value = 0;
                    }
                });
                recalculateSummary();
            });
        });

        addonChecks.forEach((check) => {
            check.addEventListener('change', () => {
                toggleAddonInput(check);
                recalculateSummary();
            });
            toggleAddonInput(check);
        });

        document.querySelectorAll('.addon-qty').forEach((qtyInput) => {
            qtyInput.addEventListener('click', (event) => {
                event.stopPropagation();
            });
            qtyInput.addEventListener('input', recalculateSummary);
        });

        useCustomerDetergent?.addEventListener('change', () => {
            enforceChemicalToggles();
            recalculateSummary();
        });

        useCustomerSoftener?.addEventListener('change', () => {
            enforceChemicalToggles();
            recalculateSummary();
        });

        enforceChemicalToggles();
        recalculateSummary();

        function getLocation() {
            const statusText = document.getElementById('location-status');
            const btnLocation = document.getElementById('btn-get-location');

            if (navigator.geolocation) {
                btnLocation.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> กำลังค้นหาตำแหน่ง...';
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
                statusText.innerHTML = "เบราว์เซอร์ของคุณไม่รองรับการดึงพิกัด GPS";
                statusText.classList.add('text-red-500');
            }
        }

        function showPosition(position) {
            // ใส่พิกัดลงในช่อง Hidden
            document.getElementById('latitude').value = position.coords.latitude;
            document.getElementById('longitude').value = position.coords.longitude;

            const btnLocation = document.getElementById('btn-get-location');
            const statusText = document.getElementById('location-status');

            btnLocation.innerHTML = '<i class="fa-solid fa-check text-green-500"></i> ดึงตำแหน่งสำเร็จแล้ว';
            btnLocation.classList.replace('text-blue-600', 'text-green-600');
            btnLocation.classList.replace('bg-blue-50', 'bg-green-50');
            btnLocation.classList.replace('border-blue-200', 'border-green-200');

            statusText.innerHTML = "รับพิกัดเรียบร้อย ระบบจะคำนวณค่าส่งจากจุดนี้";
            statusText.classList.replace('text-gray-500', 'text-green-600');

            refreshDeliveryQuote();
        }

        function showError(error) {
            const btnLocation = document.getElementById('btn-get-location');
            const statusText = document.getElementById('location-status');

            btnLocation.innerHTML = '<i class="fa-solid fa-location-crosshairs"></i> ลองดึงตำแหน่งอีกครั้ง';
            statusText.classList.replace('text-gray-500', 'text-red-500');

            switch (error.code) {
                case error.PERMISSION_DENIED:
                    statusText.innerHTML = "คุณปฏิเสธการเข้าถึงตำแหน่ง GPS กรุณาอนุญาตเพื่อคำนวณค่าบริการ";
                    break;
                case error.POSITION_UNAVAILABLE:
                    statusText.innerHTML = "ข้อมูลตำแหน่งไม่พร้อมใช้งาน";
                    break;
                case error.TIMEOUT:
                    statusText.innerHTML = "หมดเวลาในการขอตำแหน่ง";
                    break;
                default:
                    statusText.innerHTML = "เกิดข้อผิดพลาดที่ไม่ทราบสาเหตุ";
                    break;
            }
        }

        refreshDeliveryQuote();
        
        // 🌟 ระบบ Filter การแสดงแพ็กเกจ (อัปเดตรองรับ Mobile UI)
        const pkgFilterBtns = document.querySelectorAll('.pkg-filter-btn');
        const packageCards = document.querySelectorAll('.package-card-wrapper');

        pkgFilterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // 1. เคลียร์สีปุ่มทั้งหมดให้เป็นสีเทา (Unselected)
                pkgFilterBtns.forEach(b => {
                    b.classList.remove('bg-pink-500', 'text-white', 'border-transparent',
                        'shadow-sm');
                    b.classList.add('bg-white', 'text-gray-600', 'border-gray-200');
                });

                // 2. เติมสีชมพูให้ปุ่มที่ถูกกด (Selected)
                btn.classList.remove('bg-white', 'text-gray-600', 'border-gray-200');
                btn.classList.add('bg-pink-500', 'text-white', 'border-transparent', 'shadow-sm');

                const filterValue = btn.dataset.filter;

                // 3. ซ่อน/แสดง การ์ดแพ็กเกจ
                packageCards.forEach(card => {
                    const name = card.dataset.name;
                    if (filterValue === 'all') {
                        card.style.display = 'block';
                    } else {
                        if (name.includes(filterValue)) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    }
                });
            });
        });
    </script>
@endsection