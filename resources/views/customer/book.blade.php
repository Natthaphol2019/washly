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

                    <div
                        class="rounded-3xl border border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 md:p-8 shadow-sm">
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="shrink-0 bg-gradient-to-br from-pink-400 to-pink-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold shadow-md text-lg">
                                1</div>
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 tracking-tight">เลือกขนาดแพ็กเกจ
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 pl-0 md:pl-14">
                            @foreach ($packages as $package)
                                <label class="cursor-pointer relative group block h-full">
                                    <input type="radio" name="package_id" value="{{ $package->id }}"
                                        data-package-price="{{ (float) $package->price }}"
                                        data-default-addon-name="{{ $packageDefaultAddonMap[$package->id] ?? '' }}"
                                        class="peer sr-only package-option" required
                                        {{ old('package_id') == $package->id ? 'checked' : '' }}>

                                    <div
                                        class="h-full p-6 rounded-2xl border-2 border-gray-100 dark:border-slate-600 bg-gray-50/50 dark:bg-slate-700/30 hover:border-pink-300 dark:hover:border-pink-500 peer-checked:border-pink-500 peer-checked:bg-pink-50/50 dark:peer-checked:bg-slate-700/80 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-md text-center flex flex-col justify-between">
                                        <div>
                                            <div
                                                class="w-14 h-14 mx-auto bg-white dark:bg-slate-800 rounded-full flex items-center justify-center shadow-sm mb-4 transition-colors peer-checked:shadow-pink-200">
                                                <i
                                                    class="fa-solid fa-shirt text-2xl text-gray-400 dark:text-gray-500 peer-checked:text-pink-500 transition-colors"></i>
                                            </div>
                                            <h4 class="font-bold text-lg text-gray-800 dark:text-gray-100 mb-2">
                                                {{ $package->name }}</h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                                                {{ $package->description }}</p>
                                        </div>
                                        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-slate-600">
                                            <p class="text-2xl font-black text-pink-600 dark:text-pink-400">
                                                ฿{{ number_format($package->price) }}</p>
                                        </div>
                                        <div
                                            class="absolute top-4 right-4 scale-0 opacity-0 peer-checked:scale-100 peer-checked:opacity-100 text-pink-500 transition-all duration-300">
                                            <i
                                                class="fa-solid fa-circle-check text-2xl bg-white dark:bg-slate-800 rounded-full shadow-sm"></i>
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

                    <div
                        class="rounded-3xl border border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 md:p-8 shadow-sm">
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="shrink-0 bg-gradient-to-br from-pink-400 to-pink-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold shadow-md text-lg">
                                3</div>
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 tracking-tight">
                                เลือกรอบเวลารับผ้า</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 pl-0 md:pl-14">
                            @foreach ($timeSlots as $slot)
                                <label class="cursor-pointer relative group">
                                    <input type="radio" name="time_slot_id" value="{{ $slot->id }}"
                                        class="peer sr-only" required
                                        {{ old('time_slot_id') == $slot->id ? 'checked' : '' }}>
                                    <div
                                        class="px-5 py-4 rounded-2xl border-2 border-gray-100 dark:border-slate-600 bg-gray-50/50 dark:bg-slate-700/30 hover:border-pink-300 peer-checked:border-pink-500 peer-checked:bg-pink-50 dark:peer-checked:bg-slate-700 transition-all duration-200 flex items-center justify-between group-hover:shadow-sm">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-white dark:bg-slate-800 flex items-center justify-center shadow-sm text-gray-400 peer-checked:text-pink-500 transition-colors">
                                                <i class="fa-regular fa-clock"></i>
                                            </div>
                                            <span
                                                class="font-bold text-gray-700 dark:text-gray-200">{{ $slot->round_name }}</span>
                                        </div>
                                        <i
                                            class="fa-solid fa-check-circle text-xl text-pink-500 scale-0 opacity-0 peer-checked:scale-100 peer-checked:opacity-100 transition-all duration-300"></i>
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
                                4</div>
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 tracking-tight">
                                ตั้งค่าการซักและอบ</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-0 md:pl-14">
                            <div class="space-y-2">
                                <label class="block font-bold text-gray-700 dark:text-gray-300">
                                    <i class="fa-solid fa-droplet text-blue-400 mr-2"></i>อุณหภูมิน้ำ (ซัก)
                                </label>
                                <select name="wash_temp"
                                    class="w-full px-5 py-4 rounded-2xl border-2 border-gray-100 dark:border-slate-600 bg-gray-50 dark:bg-slate-700/50 text-gray-800 dark:text-gray-100 focus:border-pink-500 focus:ring-4 focus:ring-pink-500/10 outline-none transition-all cursor-pointer font-medium appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23cbd5e1%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E')] bg-[length:12px_auto] bg-no-repeat bg-[position:right_1.25rem_center]">
                                    <option value="เย็น" {{ old('wash_temp') === 'เย็น' ? 'selected' : '' }}>❄️
                                        ซักน้ำเย็น (ถนอมผ้า)</option>
                                    <option value="อุ่น" {{ old('wash_temp', 'อุ่น') === 'อุ่น' ? 'selected' : '' }}>🌡️
                                        ซักน้ำอุ่น (คราบทั่วไป)</option>
                                    <option value="ร้อน" {{ old('wash_temp') === 'ร้อน' ? 'selected' : '' }}>🔥
                                        ซักน้ำร้อน (ฆ่าเชื้อโรค)</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block font-bold text-gray-700 dark:text-gray-300">
                                    <i class="fa-solid fa-wind text-orange-400 mr-2"></i>อุณหภูมิความร้อน (อบ)
                                </label>
                                <select name="dry_temp"
                                    class="w-full px-5 py-4 rounded-2xl border-2 border-gray-100 dark:border-slate-600 bg-gray-50 dark:bg-slate-700/50 text-gray-800 dark:text-gray-100 focus:border-pink-500 focus:ring-4 focus:ring-pink-500/10 outline-none transition-all cursor-pointer font-medium appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23cbd5e1%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E')] bg-[length:12px_auto] bg-no-repeat bg-[position:right_1.25rem_center]">
                                    <option value="อุ่น" {{ old('dry_temp', 'อุ่น') === 'อุ่น' ? 'selected' : '' }}>☁️
                                        อบลมอุ่น (ถนอมผ้า)</option>
                                    <option value="ปานกลาง" {{ old('dry_temp') === 'ปานกลาง' ? 'selected' : '' }}>☀️
                                        อบความร้อนปานกลาง</option>
                                    <option value="ร้อน" {{ old('dry_temp') === 'ร้อน' ? 'selected' : '' }}>🔥
                                        อบความร้อนสูง (ผ้าหนา)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div
                        class="rounded-3xl border border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 md:p-8 shadow-sm">
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="shrink-0 bg-gradient-to-br from-pink-400 to-pink-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold shadow-md text-lg">
                                5</div>
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
                                6</div>
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 tracking-tight">
                                สถานที่รับ-ส่งผ้า</h3>
                        </div>

                        <div class="pl-0 md:pl-14">
                            <div
                                class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-4 mb-5 rounded-r-xl shadow-sm">
                                <div class="flex items-start gap-3">
                                    <i class="fa-solid fa-location-dot text-blue-500 mt-1 text-lg"></i>
                                    <p class="text-sm text-blue-800 dark:text-blue-200 leading-relaxed">
                                        ระบบจะอ้างอิงพิกัด GPS จากที่อยู่หลักของคุณ
                                        หากต้องการเปลี่ยนสถานที่รับ-ส่งผ้าแบบถาวร
                                        <a href="{{ route('customer.profile') }}"
                                            class="font-bold underline decoration-blue-400 underline-offset-2 hover:text-blue-600 transition-colors">ไปที่หน้าโปรไฟล์</a>
                                    </p>
                                </div>
                            </div>

                            <label class="block font-bold text-gray-700 dark:text-gray-300 mb-2">ที่อยู่ปัจจุบัน /
                                จุดสังเกตเพิ่มเติมให้ไรเดอร์</label>

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
                                <div class="flex justify-between items-center text-gray-600 dark:text-gray-300">
                                    <span>ค่าส่งโดยประมาณ</span>
                                    <span id="summary-delivery"
                                        class="font-medium text-gray-800 dark:text-gray-100">฿0</span>
                                </div>
                                <p id="summary-distance-note" class="text-xs text-gray-400 dark:text-gray-500">แชร์ GPS เพื่อคำนวณระยะทางจากร้าน</p>
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

            const grandTotal = subtotal + addonTotal + currentDeliveryFee;

            subtotalEl.textContent = formatBaht(subtotal);
            addonEl.textContent = formatBaht(addonTotal);
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

        packageOptions.forEach((option) => {
            option.addEventListener('change', recalculateSummary);
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
    </script>
@endsection
