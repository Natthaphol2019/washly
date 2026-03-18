@extends('layouts.admin')
@section('title', 'จัดการแพ็กเกจ - Washly Admin')
@section('page_title', 'จัดการแพ็กเกจและเมนูเสริม')

@section('content')
    <div class="max-w-7xl mx-auto w-full flex flex-col gap-6 pb-10">

        {{-- 🔹 หัวข้อหลัก --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl washly-card shadow-sm flex items-center justify-center border border-sky-100 dark:border-slate-700">
                    <i class="fa-solid fa-boxes-stacked text-2xl text-sky-500"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold washly-shell">จัดการแพ็กเกจบริการ</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">เพิ่ม แก้ไข และลบแพ็กเกจซักผ้าของคุณ</p>
                </div>
            </div>

            <button onclick="openModal('addPackageModal')" class="washly-brand-btn text-white px-5 py-2.5 rounded-xl font-semibold transition-all shadow-md hover:shadow-lg flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> เพิ่มแพ็กเกจใหม่
            </button>
        </div>

        @if (session('success'))
            <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 p-4 rounded-xl shadow-sm flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-emerald-500 text-xl"></i>
                <p class="font-medium text-emerald-700 dark:text-emerald-400">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 p-4 rounded-xl shadow-sm flex items-center gap-3">
                <i class="fa-solid fa-triangle-exclamation text-rose-500 text-xl"></i>
                <p class="font-medium text-rose-700 dark:text-rose-400">{{ session('error') }}</p>
            </div>
        @endif

        {{-- 🔍 Filter และ Search --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-5">
            <form method="GET" action="{{ route('admin.packages.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    
                    {{-- ค้นหาชื่อแพ็กเกจ --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5">
                            <i class="fa-solid fa-search"></i> ค้นหาแพ็กเกจ
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="ชื่อแพ็กเกจ..."
                            class="w-full px-3 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none transition">
                    </div>

                    {{-- กรองตามสถานะ --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5">
                            <i class="fa-solid fa-filter"></i> สถานะ
                        </label>
                        <select name="status" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none transition">
                            <option value="">ทั้งหมด</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>ใช้งาน</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>ซ่อน</option>
                        </select>
                    </div>

                    {{-- กรองตามน้ำยาเริ่มต้น --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5">
                            <i class="fa-solid fa-pump-soap"></i> น้ำยาเริ่มต้น
                        </label>
                        <select name="detergent" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none transition">
                            <option value="">ทั้งหมด</option>
                            @foreach ($detergentOptions as $detergent)
                                <option value="{{ $detergent->code }}" {{ request('detergent') == $detergent->code ? 'selected' : '' }}>{{ $detergent->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- เรียงลำดับ --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5">
                            <i class="fa-solid fa-arrow-down-a-z"></i> เรียงตาม
                        </label>
                        <select name="sort" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none transition">
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>ชื่อ A-Z</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>ชื่อ Z-A</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>ราคาต่ำ → สูง</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>ราคาสูง → ต่ำ</option>
                            <option value="created_at_desc" {{ request('sort') == 'created_at_desc' ? 'selected' : '' }}>ใหม่ → เก่า</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 pt-2">
                    <button type="submit" class="bg-sky-500 hover:bg-sky-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm flex items-center gap-2">
                        <i class="fa-solid fa-magnifying-glass"></i> ค้นหา
                    </button>
                    <a href="{{ route('admin.packages.index') }}" class="bg-gray-100 hover:bg-gray-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-700 dark:text-gray-300 px-5 py-2.5 rounded-xl text-sm font-semibold transition flex items-center gap-2">
                        <i class="fa-solid fa-rotate-left"></i> ล้างตัวกรอง
                    </a>
                    
                    @if(request()->hasAny(['search', 'status', 'detergent', 'sort']))
                        <div class="flex items-center gap-2 ml-auto text-xs text-gray-500 dark:text-gray-400">
                            <span class="font-semibold text-sky-600 dark:text-sky-400">{{ $packages->count() }}</span> แพ็กเกจ
                        </div>
                    @endif
                </div>
            </form>
        </div>

        {{-- สรุปผลการค้นหา --}}
        @if(request()->hasAny(['search', 'status', 'detergent']))
            <div class="flex items-center justify-between text-sm bg-sky-50 dark:bg-sky-900/20 border border-sky-100 dark:border-sky-800 px-4 py-2.5 rounded-xl">
                <p class="text-sky-700 dark:text-sky-400">
                    <i class="fa-solid fa-filter"></i> 
                    กำลังแสดงผลลัพธ์ที่กรอง: 
                    @if(request('search')) <span class="font-semibold">ค้นหา="{{ request('search') }}"</span> @endif
                    @if(request('status') == 'active') <span class="font-semibold">สถานะ="ใช้งาน"</span> @endif
                    @if(request('status') == 'inactive') <span class="font-semibold">สถานะ="ซ่อน"</span> @endif
                    @if(request('detergent')) <span class="font-semibold">น้ำยา="{{ $detergentOptions->firstWhere('code', request('detergent'))?->name ?? request('detergent') }}"</span> @endif
                </p>
                <span class="text-sky-600 dark:text-sky-400 font-bold">{{ $packages->count() }} แพ็กเกจ</span>
            </div>
        @endif

        {{-- 🔹 ตารางแพ็กเกจหลัก --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-600 dark:text-slate-300 text-sm border-b border-slate-200 dark:border-slate-700">
                        <tr>
                            <th class="p-4 font-semibold w-px whitespace-nowrap text-center">ลำดับ</th>
                            <th class="p-4 font-semibold">ชื่อแพ็กเกจ / รายละเอียด</th>
                            <th class="p-4 font-semibold whitespace-nowrap">น้ำยาอัตโนมัติ</th>
                            <th class="p-4 font-semibold whitespace-nowrap text-right">ราคา (บาท)</th>
                            <th class="p-4 font-semibold text-center whitespace-nowrap">สถานะ</th>
                            <th class="p-4 font-semibold text-center whitespace-nowrap w-px">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50 washly-shell">
                        @forelse($packages as $index => $package)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <td class="p-4 text-center text-gray-500 font-medium">{{ $index + 1 }}</td>
                                <td class="p-4">
                                    <div class="flex items-center gap-4">
                                        @if($package->image_path)
                                            <img src="{{ asset('storage/' . $package->image_path) }}" alt="{{ $package->name }}" class="w-14 h-14 rounded-xl object-cover border border-slate-200 dark:border-slate-600 shadow-sm shrink-0">
                                        @else
                                            <div class="w-14 h-14 rounded-xl bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 flex items-center justify-center shrink-0">
                                                <i class="fa-solid fa-shirt text-xl text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-bold text-gray-800 dark:text-gray-100 text-base">{{ $package->name }}</p>
                                            <p class="text-sm text-gray-500 mt-1 line-clamp-1">{{ $package->description ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ $detergentOptions->firstWhere('code', $package->default_detergent_code)?->name ?? 'ใช้ค่าเริ่มต้นระบบ' }}
                                </td>
                                <td class="p-4 text-right">
                                    <span class="text-lg font-black text-sky-500 dark:text-sky-400">฿{{ number_format($package->price) }}</span>
                                </td>
                                <td class="p-4 text-center whitespace-nowrap">
                                    @if ($package->is_active)
                                        <span class="bg-emerald-50 text-emerald-600 border border-emerald-200 dark:bg-emerald-900/30 dark:border-emerald-800 px-3 py-1 rounded-full text-xs font-bold"><i class="fa-solid fa-eye"></i> ใช้งาน</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-500 border border-gray-200 dark:bg-slate-700 dark:border-slate-600 px-3 py-1 rounded-full text-xs font-bold"><i class="fa-solid fa-eye-slash"></i> ซ่อน</span>
                                    @endif
                                </td>
                                <td class="p-4 text-center whitespace-nowrap w-px">
                                    <div class="flex items-center gap-2 justify-center">
                                        <button type="button" onclick="openEditModal({{ $package->id }}, '{{ addslashes($package->name) }}', {{ $package->price }}, '{{ preg_replace("/\r|\n/", ' ', addslashes($package->description)) }}', '{{ $package->default_detergent_code }}', {{ $package->is_active ? 'true' : 'false' }})" 
                                            class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-500 hover:bg-amber-100 transition flex items-center justify-center border border-amber-200 dark:border-amber-800/50">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </button>
                                        <form action="{{ route('admin.packages.destroy', $package->id) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(this.closest('form'), '{{ addslashes($package->name) }}')" 
                                                class="w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-900/30 text-rose-500 hover:bg-rose-100 transition flex items-center justify-center border border-rose-200 dark:border-rose-800/50">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-10 text-center text-gray-400">
                                    <i class="fa-solid fa-box-open text-4xl mb-3 opacity-50"></i>
                                    <p class="font-medium">ยังไม่มีแพ็กเกจในระบบ</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 🔹 โซนเมนูเสริม (Add-ons) --}}
        @php
            $addonSections = [
                'detergent' => ['title' => 'น้ำยาซักผ้า', 'icon' => 'fa-soap'],
                'softener' => ['title' => 'น้ำยาปรับผ้านุ่ม', 'icon' => 'fa-wind'],
                'service' => ['title' => 'บริการเสริมอื่นๆ', 'icon' => 'fa-sparkles'],
            ];
        @endphp

        <div class="mt-8 space-y-6">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold washly-shell flex items-center gap-2">
                        <i class="fa-solid fa-flask-vial text-sky-500"></i> เพิ่มเมนูเสริม (Add-ons)
                    </h2>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    @foreach ($addonSections as $category => $meta)
                        <form action="{{ route('admin.addons.store') }}" method="POST" enctype="multipart/form-data" class="rounded-2xl border border-slate-200 dark:border-slate-700 p-5 space-y-4 bg-slate-50/50 dark:bg-slate-800/50">
                            @csrf
                            <input type="hidden" name="category" value="{{ $category }}">

                            <h3 class="font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2">
                                <i class="fa-solid {{ $meta['icon'] }} text-sky-500"></i> {{ $meta['title'] }}
                            </h3>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">ชื่อเมนู <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" required class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-3 py-2 bg-white dark:bg-slate-900 focus:ring-2 focus:ring-sky-500 outline-none text-sm">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">ราคา (บาท) <span class="text-rose-500">*</span></label>
                                <input type="number" name="unit_price" min="0" required class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-3 py-2 bg-white dark:bg-slate-900 focus:ring-2 focus:ring-sky-500 outline-none text-sm">
                            </div>

                            {{-- 👇 เพิ่มฟิลด์อัปโหลดรูปภาพตรงนี้ครับ --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">รูปเมนู (ถ้ามี)</label>
                                <input type="file" name="image" accept="image/png,image/jpeg,image/webp" 
                                    class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-3 py-1.5 bg-white dark:bg-slate-900 focus:ring-2 focus:ring-sky-500 outline-none text-sm file:mr-2 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-sky-100 file:text-sky-700 hover:file:bg-sky-200 cursor-pointer">
                            </div>

                            <div class="flex flex-col gap-2 pt-2">
                                <label class="text-xs flex items-center gap-2 font-medium cursor-pointer"><input type="checkbox" name="is_active" value="1" checked class="text-sky-500 rounded"> เปิดใช้งาน</label>
                                @if ($category !== 'service')
                                    <label class="text-xs flex items-center gap-2 font-medium cursor-pointer"><input type="checkbox" name="is_default" value="1" class="text-sky-500 rounded"> ตั้งเป็นค่าเริ่มต้นฟรี (0 บาท)</label>
                                @endif
                            </div>

                            <button type="submit" class="w-full washly-brand-btn text-white px-4 py-2.5 rounded-xl text-sm font-bold shadow-md hover:brightness-110 mt-2">เพิ่ม{{ $meta['title'] }}</button>
                        </form>
                    @endforeach
                </div>
            </div>

            {{-- แสดงรายการเมนูเสริม --}}
            @foreach ($addonSections as $category => $meta)
                @php $items = $addonOptions->where('category', $category)->values(); @endphp
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="p-5 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/50 flex justify-between items-center">
                        <h3 class="text-base font-bold washly-shell flex items-center gap-2">
                            <i class="fa-solid {{ $meta['icon'] }} text-sky-500"></i> รายการ{{ $meta['title'] }}
                        </h3>
                        <span class="text-xs font-semibold text-gray-500 bg-gray-200 dark:bg-slate-700 px-2 py-1 rounded-md">{{ $items->count() }} รายการ</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 dark:bg-slate-800/30 text-slate-500 border-b border-slate-200 dark:border-slate-700">
                                <tr>
                                    <th class="px-5 py-3 text-left font-semibold w-24">รหัส Code</th>
                                    <th class="px-5 py-3 text-left font-semibold">ชื่อเมนู</th>
                                    <th class="px-5 py-3 text-left font-semibold">รูปภาพ</th>
                                    <th class="px-5 py-3 text-left font-semibold w-28">ราคา (บาท)</th>
                                    <th class="px-5 py-3 text-left font-semibold">สถานะ</th>
                                    <th class="px-5 py-3 text-left font-semibold">ค่าเริ่มต้น</th>
                                    <th class="px-5 py-3 text-right font-semibold">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                                @forelse($items as $addon)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
                                        <td class="hidden">
                                            <form id="form-addon-{{ $addon->id }}" action="{{ route('admin.addons.update', $addon->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="category" value="{{ $category }}">
                                            </form>
                                        </td>
                                        
                                        <td class="px-5 py-3 font-medium text-gray-500 text-xs">
                                            {{ $addon->code }}
                                        </td>
                                        
                                        <td class="px-5 py-3">
                                            <input form="form-addon-{{ $addon->id }}" type="text" name="name" value="{{ $addon->name }}" class="w-full border border-slate-300 dark:border-slate-600 rounded-lg px-3 py-1.5 bg-white dark:bg-slate-900 focus:ring-1 focus:ring-sky-500 outline-none text-sm font-medium">
                                        </td>
                                        
                                        {{-- 👇 ส่วนแสดงและแก้ไขรูปภาพของ Addon --}}
                                        <td class="px-5 py-3">
                                            <div class="flex items-center gap-3">
                                                @if ($addon->image_path)
                                                    <img src="{{ asset('storage/' . $addon->image_path) }}" alt="{{ $addon->name }}" class="w-10 h-10 rounded-lg object-cover border border-slate-200 dark:border-slate-600 shadow-sm shrink-0">
                                                @else
                                                    <div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 flex items-center justify-center shadow-sm shrink-0">
                                                        <i class="fa-solid fa-image text-gray-400"></i>
                                                    </div>
                                                @endif
                                                <input form="form-addon-{{ $addon->id }}" type="file" name="image" accept="image/png,image/jpeg,image/webp" class="text-[10px] w-40 file:mr-2 file:py-1 file:px-2 file:rounded-full file:border-0 file:font-semibold file:bg-sky-100 file:text-sky-700 hover:file:bg-sky-200 cursor-pointer">
                                            </div>
                                        </td>

                                        <td class="px-5 py-3">
                                            <input form="form-addon-{{ $addon->id }}" type="number" min="0" name="unit_price" value="{{ $addon->unit_price }}" class="w-full border border-slate-300 dark:border-slate-600 rounded-lg px-3 py-1.5 bg-white dark:bg-slate-900 focus:ring-1 focus:ring-sky-500 outline-none text-sm font-bold text-sky-600 dark:text-sky-400">
                                        </td>
                                        <td class="px-5 py-3">
                                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                                <input form="form-addon-{{ $addon->id }}" type="checkbox" name="is_active" value="1" {{ $addon->is_active ? 'checked' : '' }} class="w-4 h-4 text-sky-500 rounded">
                                                <span class="text-xs text-gray-600 dark:text-gray-300">ใช้งาน</span>
                                            </label>
                                        </td>
                                        <td class="px-5 py-3">
                                            @if ($category !== 'service')
                                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                                    <input form="form-addon-{{ $addon->id }}" type="checkbox" name="is_default" value="1" {{ $addon->is_default ? 'checked' : '' }} class="w-4 h-4 text-emerald-500 rounded">
                                                    <span class="text-xs text-gray-600 dark:text-gray-300">Default</span>
                                                </label>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3 text-right">
                                            <div class="inline-flex gap-2">
                                                <button type="button" onclick="confirmUpdateAddon(document.getElementById('form-addon-{{ $addon->id }}'), '{{ $addon->name }}')" class="text-sky-600 bg-sky-50 hover:bg-sky-100 p-2 rounded-lg transition" title="บันทึกการแก้ไข"><i class="fa-solid fa-floppy-disk"></i></button>
                                                <button type="button" onclick="confirmDeleteAddon('{{ route('admin.addons.destroy', $addon->id) }}', '{{ $addon->name }}')" class="text-rose-600 bg-rose-50 hover:bg-rose-100 p-2 rounded-lg transition" title="ลบเมนูนี้"><i class="fa-solid fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-6 text-center text-gray-400 text-xs">ยังไม่มีข้อมูล</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>

        <form id="addonDeleteForm" method="POST" class="hidden">
            @csrf @method('DELETE')
        </form>
    </div>

    {{-- Modal เพิ่มแพ็กเกจ --}}
    <div id="addPackageModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('addPackageModal')"></div>
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-md mx-4 z-10 p-6 md:p-8 border border-slate-100 dark:border-slate-700">
            <h2 class="text-xl font-bold washly-brand-text mb-6 flex items-center gap-2"><i class="fa-solid fa-box text-sky-500"></i> สร้างแพ็กเกจใหม่</h2>
            <form action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">ชื่อแพ็กเกจ <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required class="w-full border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">ราคา (บาท) <span class="text-rose-500">*</span></label>
                    <input type="number" name="price" min="0" required class="w-full border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">รายละเอียด</label>
                    <textarea name="description" rows="2" class="w-full border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">รูปภาพ</label>
                    <input type="file" name="image" accept="image/*" class="w-full text-sm border border-slate-300 dark:border-slate-600 rounded-xl px-3 py-2 bg-slate-50 dark:bg-slate-900/50 file:mr-3 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-sky-100 file:text-sky-700 cursor-pointer">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">น้ำยาเริ่มต้น</label>
                    <select name="default_detergent_code" class="w-full border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500">
                        <option value="">ใช้ค่าเริ่มต้น</option>
                        @foreach ($detergentOptions as $detergent)
                            <option value="{{ $detergent->code }}">{{ $detergent->name }} (+฿{{ $detergent->unit_price }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('addPackageModal')" class="px-5 py-2.5 rounded-xl font-medium bg-slate-100 text-slate-600 hover:bg-slate-200 transition">ยกเลิก</button>
                    <button type="submit" class="washly-brand-btn text-white px-6 py-2.5 rounded-xl font-bold shadow-md hover:brightness-110">บันทึก</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal แก้ไขแพ็กเกจ --}}
    <div id="editPackageModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('editPackageModal')"></div>
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-md mx-4 z-10 p-6 md:p-8 border border-slate-100 dark:border-slate-700">
            <h2 class="text-xl font-bold text-amber-500 mb-6 flex items-center gap-2"><i class="fa-solid fa-pen-to-square"></i> แก้ไขแพ็กเกจ</h2>
            <form id="editForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">ชื่อแพ็กเกจ <span class="text-rose-500">*</span></label>
                    <input type="text" id="edit_name" name="name" required class="w-full border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">ราคา (บาท) <span class="text-rose-500">*</span></label>
                    <input type="number" id="edit_price" name="price" min="0" required class="w-full border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">รายละเอียด</label>
                    <textarea id="edit_description" name="description" rows="2" class="w-full border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">เปลี่ยนรูปภาพ (ถ้ามี)</label>
                    <input type="file" name="image" accept="image/*" class="w-full text-sm border border-slate-300 dark:border-slate-600 rounded-xl px-3 py-2 bg-slate-50 dark:bg-slate-900/50 file:mr-3 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-amber-100 file:text-amber-700 cursor-pointer">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">น้ำยาเริ่มต้น</label>
                    <select id="edit_default_detergent_code" name="default_detergent_code" class="w-full border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500">
                        <option value="">ใช้ค่าเริ่มต้น</option>
                        @foreach ($detergentOptions as $detergent)
                            <option value="{{ $detergent->code }}">{{ $detergent->name }} (+฿{{ $detergent->unit_price }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="flex items-center gap-2 cursor-pointer pt-2 font-medium text-sm text-slate-700 dark:text-slate-300">
                        <input type="checkbox" id="edit_is_active" name="is_active" value="1" class="w-4 h-4 text-amber-500 rounded"> เปิดใช้งาน
                    </label>
                </div>
                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('editPackageModal')" class="px-5 py-2.5 rounded-xl font-medium bg-slate-100 text-slate-600 hover:bg-slate-200 transition">ยกเลิก</button>
                    <button type="submit" class="bg-amber-500 text-white px-6 py-2.5 rounded-xl font-bold shadow-md hover:bg-amber-600 transition">บันทึก</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

        function openEditModal(id, name, price, description, defaultDetergentCode, isActive) {
            document.getElementById('editForm').action = `/admin/packages/${id}`;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_price').value = price;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_default_detergent_code').value = defaultDetergentCode || '';
            document.getElementById('edit_is_active').checked = isActive;
            openModal('editPackageModal');
        }

        function confirmDelete(form, packageName) {
            Swal.fire({
                title: 'ลบแพ็กเกจนี้?',
                html: `คุณแน่ใจหรือไม่ที่จะลบ <b>${packageName}</b> ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444', cancelButtonColor: '#94a3b8',
                confirmButtonText: 'ลบเลย', cancelButtonText: 'ยกเลิก',
                background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#1e293b',
                customClass: { popup: 'rounded-3xl' }
            }).then((result) => { if (result.isConfirmed) form.submit(); })
        }

        function confirmDeleteAddon(actionUrl, addonName) {
            Swal.fire({
                title: 'ลบเมนูเสริม?',
                html: `ต้องการลบ <b>${addonName}</b> หรือไม่?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444', cancelButtonColor: '#94a3b8',
                confirmButtonText: 'ลบเลย', cancelButtonText: 'ยกเลิก',
                background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#1e293b',
                customClass: { popup: 'rounded-3xl' }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('addonDeleteForm');
                    form.action = actionUrl;
                    form.submit();
                }
            });
        }

        function confirmUpdateAddon(form, addonName) {
            Swal.fire({
                title: 'บันทึกการแก้ไข?',
                html: `ยืนยันแก้ไข <b>${addonName}</b>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0ea5e9', cancelButtonColor: '#94a3b8',
                confirmButtonText: 'บันทึก', cancelButtonText: 'ยกเลิก',
                background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#1e293b',
                customClass: { popup: 'rounded-3xl' }
            }).then((result) => { if (result.isConfirmed) form.submit(); });
        }
    </script>
@endsection