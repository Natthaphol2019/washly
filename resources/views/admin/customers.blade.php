@extends('layouts.admin')
@section('title', 'จัดการลูกค้า - Washly Admin')
@section('page_title', 'จัดการรายชื่อลูกค้า')

@section('content')
    <div class="max-w-7xl mx-auto w-full flex flex-col gap-6 pb-10">

        {{-- 🔹 หัวข้อหลัก --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl washly-card shadow-sm flex items-center justify-center border border-sky-100 dark:border-slate-700">
                    <i class="fa-solid fa-users text-2xl text-sky-500"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold washly-shell">จัดการรายชื่อลูกค้า</h1>
                    <p class="text-sm text-gray-500 mt-1">เพิ่ม แก้ไข และลบบัญชีผู้ใช้งานในระบบ</p>
                </div>
            </div>

            <button onclick="openModal('addCustomerModal')" class="washly-brand-btn text-white px-5 py-2.5 rounded-xl font-semibold transition-all shadow-md hover:shadow-lg flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> เพิ่มลูกค้าใหม่
            </button>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 p-4 rounded-xl shadow-sm flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-emerald-500 text-xl"></i>
                <p class="font-medium text-emerald-700 dark:text-emerald-400">{{ session('success') }}</p>
            </div>
        @endif
        @if($errors->any())
            <div class="bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 p-4 rounded-xl shadow-sm">
                <ul class="list-disc list-inside text-sm text-rose-600 dark:text-rose-400">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- 🔍 Filter และ Search --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-5">
            <form method="GET" action="{{ route('admin.customers.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    
                    {{-- ค้นหาชื่อ/username --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5">
                            <i class="fa-solid fa-search"></i> ค้นหา
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="ชื่อ หรือ username..."
                            class="w-full px-3 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none transition">
                    </div>

                    {{-- กรองตามสถานะประเมิน --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5">
                            <i class="fa-solid fa-route"></i> สถานะประเมิน
                        </label>
                        <select name="distance_status" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none transition">
                            <option value="">ทั้งหมด</option>
                            <option value="assessed" {{ request('distance_status') == 'assessed' ? 'selected' : '' }}>ประเมินแล้ว</option>
                            <option value="pending" {{ request('distance_status') == 'pending' ? 'selected' : '' }}>ยังไม่ประเมิน</option>
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
                            <option value="distance_asc" {{ request('sort') == 'distance_asc' ? 'selected' : '' }}>ระยะทางใกล้ → ไกล</option>
                            <option value="distance_desc" {{ request('sort') == 'distance_desc' ? 'selected' : '' }}>ระยะทางไกล → ใกล้</option>
                            <option value="created_at_desc" {{ request('sort') == 'created_at_desc' ? 'selected' : '' }}>ใหม่ → เก่า</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 pt-2">
                    <button type="submit" class="bg-sky-500 hover:bg-sky-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm flex items-center gap-2">
                        <i class="fa-solid fa-magnifying-glass"></i> ค้นหา
                    </button>
                    <a href="{{ route('admin.customers.index') }}" class="bg-gray-100 hover:bg-gray-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-700 dark:text-gray-300 px-5 py-2.5 rounded-xl text-sm font-semibold transition flex items-center gap-2">
                        <i class="fa-solid fa-rotate-left"></i> ล้างตัวกรอง
                    </a>
                    
                    @if(request()->hasAny(['search', 'distance_status', 'sort']))
                        <div class="flex items-center gap-2 ml-auto text-xs text-gray-500 dark:text-gray-400">
                            <span class="font-semibold text-sky-600 dark:text-sky-400">{{ $customers->count() }}</span> คน
                        </div>
                    @endif
                </div>
            </form>
        </div>

        {{-- สรุปผลการค้นหา --}}
        @if(request()->hasAny(['search', 'distance_status']))
            <div class="flex items-center justify-between text-sm bg-sky-50 dark:bg-sky-900/20 border border-sky-100 dark:border-sky-800 px-4 py-2.5 rounded-xl">
                <p class="text-sky-700 dark:text-sky-400">
                    <i class="fa-solid fa-filter"></i> 
                    กำลังแสดงผลลัพธ์ที่กรอง: 
                    @if(request('search')) <span class="font-semibold">ค้นหา="{{ request('search') }}"</span> @endif
                    @if(request('distance_status') == 'assessed') <span class="font-semibold">ประเมินแล้ว</span> @endif
                    @if(request('distance_status') == 'pending') <span class="font-semibold">ยังไม่ประเมิน</span> @endif
                </p>
                <span class="text-sky-600 dark:text-sky-400 font-bold">{{ $customers->count() }} คน</span>
            </div>
        @endif

        {{-- 🔹 ตารางรายชื่อลูกค้า --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-600 dark:text-slate-300 border-b border-slate-200 dark:border-slate-700">
                        <tr>
                            <th class="p-4 font-semibold w-px whitespace-nowrap text-center">ลำดับ</th>
                            <th class="p-4 font-semibold w-[25%] min-w-[200px]">ชื่อ-นามสกุล / ระยะทาง</th>
                            <th class="p-4 font-semibold">บัญชี / ติดต่อ</th>
                            <th class="p-4 font-semibold w-[30%] min-w-[220px]">ที่อยู่ / พิกัดลูกค้า</th>
                            <th class="p-4 font-semibold whitespace-nowrap">วันที่สมัคร</th>
                            <th class="p-4 font-semibold text-center whitespace-nowrap w-px">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50 washly-shell">
                        @forelse($customers as $index => $customer)
                            {{-- 🌟 ไฮไลท์พื้นหลังเป็นสีแดงอ่อน ถ้าระยะทางยังว่าง --}}
                            <tr class="transition-colors {{ $customer->delivery_distance === null ? 'bg-rose-50/40 dark:bg-rose-900/10 hover:bg-rose-100/50 dark:hover:bg-rose-900/20' : 'hover:bg-slate-50 dark:hover:bg-slate-700/50' }}">
                                <td class="p-4 text-center text-gray-500 font-medium">{{ $index + 1 }}</td>
                                
                                {{-- 1. ชื่อ และ ระยะทาง --}}
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-sky-100 dark:bg-sky-900/40 text-sky-600 dark:text-sky-400 flex items-center justify-center font-bold shrink-0">
                                            {{ mb_substr($customer->fullname, 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-800 dark:text-gray-100">{{ $customer->fullname }}</span>
                                            
                                            {{-- 🌟 โชว์ระยะทาง หรือ ไฮไลท์เตือนแอดมิน --}}
                                            @if($customer->delivery_distance !== null)
                                                <span class="text-[10px] text-sky-500 font-medium mt-0.5"><i class="fa-solid fa-route"></i> ระยะทาง {{ $customer->delivery_distance }} กม.</span>
                                            @else
                                                <span class="text-[10px] text-rose-500 font-bold mt-0.5 flex items-center gap-1.5">
                                                    <span class="relative flex h-2 w-2">
                                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                                                    </span>
                                                    ต้องประเมินระยะทาง!
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- 2. บัญชี และ เบอร์โทร --}}
                                <td class="p-4">
                                    <p class="text-gray-800 dark:text-gray-200 font-medium"><i class="fa-solid fa-at text-gray-400 mr-1"></i>{{ $customer->username }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"><i class="fa-solid fa-phone text-gray-400 mr-1"></i>{{ $customer->phone ?? '-' }}</p>
                                </td>

                                {{-- 🌟 3. ที่อยู่ และ ปุ่มเปิดแผนที่ --}}
                                <td class="p-4 whitespace-normal">
                                    @if($customer->address)
                                        <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2 leading-relaxed" title="{{ $customer->address }}">{{ Str::limit($customer->address, 60) }}</p>
                                        
                                        @php
                                            // สร้าง Link ไป Google Maps (ถ้ามีละติจูด ให้ใช้พิกัด ถ้าไม่มีให้ใช้ที่อยู่ค้นหา)
                                            $mapQuery = ($customer->latitude && $customer->longitude) 
                                                ? "{$customer->latitude},{$customer->longitude}" 
                                                : urlencode(preg_replace('/\s+/', ' ', $customer->address));
                                        @endphp
                                        <a href="https://www.google.com/maps/search/?api=1&query={{ $mapQuery }}" target="_blank" class="inline-flex items-center gap-1.5 mt-2 text-[10px] font-bold text-blue-600 hover:text-white dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-500 dark:hover:bg-blue-500 px-2.5 py-1 rounded-md border border-blue-200 dark:border-blue-800/50 transition-all">
                                            <i class="fa-solid fa-map-location-dot"></i> ดูเส้นทางแผนที่
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400 italic">ยังไม่ระบุที่อยู่</span>
                                    @endif
                                </td>

                                <td class="p-4 text-gray-500">{{ $customer->created_at ? $customer->created_at->format('d/m/Y H:i') : '-' }}</td>
                                
                                {{-- 4. จัดการ --}}
                                <td class="p-4 text-center whitespace-nowrap w-px">
                                    <div class="flex items-center gap-2 justify-center">
                                        
                                        <button type="button" onclick="openViewModal('{{ addslashes($customer->fullname) }}', '{{ addslashes($customer->username) }}', '{{ addslashes($customer->phone ?? '-') }}', '{{ addslashes(preg_replace('/\r|\n/', ' ', $customer->address ?? '-')) }}', '{{ $customer->created_at ? $customer->created_at->format('d/m/Y H:i') : '-' }}', '{{ $customer->delivery_distance ?? '-' }}')" class="w-8 h-8 rounded-lg bg-sky-50 dark:bg-sky-900/30 text-sky-600 hover:bg-sky-100 transition flex items-center justify-center border border-sky-200 dark:border-sky-800/50">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                        </button>
                                        
                                        {{-- 🌟 ปุ่ม Edit จะกลายเป็นสีแดงกะพริบ ถ้ายังไม่ได้กรอกระยะทาง --}}
                                        @php
                                            $editBtnClass = $customer->delivery_distance === null 
                                                ? 'bg-rose-100 text-rose-600 border-rose-300 hover:bg-rose-200 shadow-[0_0_8px_rgba(244,63,94,0.4)] animate-pulse' 
                                                : 'bg-amber-50 dark:bg-amber-900/30 text-amber-500 hover:bg-amber-100 border-amber-200 dark:border-amber-800/50';
                                        @endphp
                                        <button type="button" onclick="openEditModal({{ $customer->id }}, '{{ addslashes($customer->fullname) }}', '{{ addslashes($customer->username) }}', '{{ addslashes($customer->phone ?? '') }}', '{{ addslashes(preg_replace('/\r|\n/', ' ', $customer->address ?? '')) }}', '{{ $customer->delivery_distance ?? '' }}')" class="w-8 h-8 rounded-lg transition flex items-center justify-center border {{ $editBtnClass }}">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </button>

                                        <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" class="m-0">
                                            @csrf @method('DELETE')
                                            <button type="button" onclick="confirmDelete(this.closest('form'), '{{ addslashes($customer->fullname) }}')" class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 hover:bg-rose-50 hover:text-rose-500 transition flex items-center justify-center border border-gray-200 dark:border-gray-700">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-10 text-center text-gray-400">
                                    <i class="fa-solid fa-users-slash text-4xl mb-3 opacity-50"></i>
                                    <p class="font-medium">ยังไม่มีข้อมูลลูกค้าในระบบ</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 🟢 Modal: เพิ่มลูกค้าใหม่ --}}
    <div id="addCustomerModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('addCustomerModal')"></div>
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-lg mx-4 z-10 transform transition-all p-6 md:p-8 max-h-[90vh] overflow-y-auto border border-slate-100 dark:border-slate-700">
            <h2 class="text-xl font-bold washly-brand-text mb-6 flex items-center gap-2">
                <i class="fa-solid fa-user-plus text-sky-500"></i> เพิ่มลูกค้าใหม่
            </h2>
            <form action="{{ route('admin.customers.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">ชื่อ-นามสกุล <span class="text-rose-500">*</span></label>
                        <input type="text" name="fullname" required class="w-full border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Username <span class="text-rose-500">*</span></label>
                            <input type="text" name="username" required class="w-full border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">เบอร์โทรศัพท์</label>
                            <input type="tel" name="phone" class="w-full border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">ที่อยู่</label>
                        <textarea name="address" rows="3" class="w-full border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500 resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">รหัสผ่าน <span class="text-rose-500">*</span></label>
                        <input type="password" name="password" required minlength="8" class="w-full border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500">
                    </div>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('addCustomerModal')" class="px-5 py-2.5 rounded-xl font-medium bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">ยกเลิก</button>
                    <button type="submit" class="washly-brand-btn text-white px-6 py-2.5 rounded-xl font-bold shadow-md hover:brightness-110">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
    </div>

    {{-- 🔵 Modal: ดูข้อมูลลูกค้า --}}
    <div id="viewCustomerModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('viewCustomerModal')"></div>
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-lg mx-4 z-10 transform transition-all p-6 md:p-8 max-h-[90vh] overflow-y-auto border border-slate-100 dark:border-slate-700">
            <h2 class="text-xl font-bold washly-brand-text mb-6 flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-sky-500"></i> ข้อมูลลูกค้า
            </h2>

            <div class="space-y-4">
                <div class="rounded-xl border border-slate-200 dark:border-slate-700 p-4 bg-slate-50 dark:bg-slate-900/50">
                    <p class="text-xs font-semibold text-slate-500">ชื่อ-นามสกุล</p>
                    <p id="view_fullname" class="mt-1 text-base font-bold text-slate-800 dark:text-white">-</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="rounded-xl border border-slate-200 dark:border-slate-700 p-4 bg-slate-50 dark:bg-slate-900/50">
                        <p class="text-xs font-semibold text-slate-500">Username</p>
                        <p id="view_username" class="mt-1 text-sm font-bold text-slate-800 dark:text-white">-</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 dark:border-slate-700 p-4 bg-slate-50 dark:bg-slate-900/50">
                        <p class="text-xs font-semibold text-slate-500">เบอร์โทรศัพท์</p>
                        <p id="view_phone" class="mt-1 text-sm font-bold text-slate-800 dark:text-white">-</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="rounded-xl border border-slate-200 dark:border-slate-700 p-4 bg-slate-50 dark:bg-slate-900/50">
                        <p class="text-xs font-semibold text-slate-500">วันที่สมัคร</p>
                        <p id="view_created_at" class="mt-1 text-sm font-bold text-slate-800 dark:text-white">-</p>
                    </div>
                    <div class="rounded-xl border border-sky-200 dark:border-sky-800/50 p-4 bg-sky-50/50 dark:bg-sky-900/20">
                        <p class="text-xs font-semibold text-sky-600 dark:text-sky-400">ระยะทางจากร้าน (กม.)</p>
                        <p id="view_delivery_distance" class="mt-1 text-sm font-bold text-sky-700 dark:text-sky-300">-</p>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 dark:border-slate-700 p-4 bg-slate-50 dark:bg-slate-900/50">
                    <p class="text-xs font-semibold text-slate-500">ที่อยู่</p>
                    <p id="view_address" class="mt-1 text-sm leading-relaxed text-slate-700 dark:text-slate-300">-</p>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="button" onclick="closeModal('viewCustomerModal')" class="px-6 py-2.5 rounded-xl font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors border border-slate-200 dark:border-slate-700 dark:bg-slate-700 dark:text-white dark:hover:bg-slate-600">ปิดหน้าต่าง</button>
            </div>
        </div>
    </div>

    {{-- 🟡 Modal: แก้ไขลูกค้า --}}
    <div id="editCustomerModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('editCustomerModal')"></div>
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-lg mx-4 z-10 transform transition-all p-6 md:p-8 max-h-[90vh] overflow-y-auto border border-slate-100 dark:border-slate-700">
            <h2 class="text-xl font-bold text-amber-500 mb-6 flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square"></i> แก้ไขข้อมูลลูกค้า
            </h2>
            <form id="editForm" method="POST">
                @csrf @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">ชื่อ-นามสกุล <span class="text-rose-500">*</span></label>
                        <input type="text" id="edit_fullname" name="fullname" required class="w-full border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Username <span class="text-rose-500">*</span></label>
                            <input type="text" id="edit_username" name="username" required class="w-full border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">เบอร์โทรศัพท์</label>
                            <input type="tel" id="edit_phone" name="phone" class="w-full border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">ที่อยู่</label>
                        <textarea id="edit_address" name="address" rows="3" class="w-full border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 resize-none"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            ระยะทางรับ-ส่งจากร้าน (กิโลเมตร) 
                            <span class="text-xs text-slate-400 dark:text-slate-500 font-normal ml-1">(< 1.5 กม. ฟรี, จากนั้น กม.ละ 20 บาท)</span>
                        </label>
                        <input type="number" step="0.1" min="0" id="edit_delivery_distance" name="delivery_distance" class="w-full border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500" placeholder="เช่น 2.5">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">เปลี่ยนรหัสผ่าน <span class="text-xs text-slate-400 font-normal">(เว้นว่างไว้หากไม่ต้องการเปลี่ยน)</span></label>
                        <input type="password" name="password" minlength="8" class="w-full border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500">
                    </div>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('editCustomerModal')" class="px-5 py-2.5 rounded-xl font-medium bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">ยกเลิก</button>
                    <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-2.5 rounded-xl font-bold shadow-md transition-colors">อัปเดตข้อมูล</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script ควบคุม Modal และ SweetAlert --}}
    <script>
        function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

        function openViewModal(fullname, username, phone, address, createdAt, distance) {
            document.getElementById('view_fullname').textContent = fullname || '-';
            document.getElementById('view_username').textContent = username || '-';
            document.getElementById('view_phone').textContent = phone || '-';
            document.getElementById('view_address').textContent = address || '-';
            document.getElementById('view_created_at').textContent = createdAt || '-';
            
            let distanceText = distance !== '-' && distance !== '' ? distance + ' กม.' : 'ยังไม่ประเมิน';
            document.getElementById('view_delivery_distance').textContent = distanceText;
            
            openModal('viewCustomerModal');
        }

        function openEditModal(id, fullname, username, phone, address, distance) {
            document.getElementById('editForm').action = `/admin/customers/${id}`;
            document.getElementById('edit_fullname').value = fullname;
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_phone').value = phone;
            document.getElementById('edit_address').value = address;
            document.getElementById('edit_delivery_distance').value = distance !== '-' ? distance : '';
            openModal('editCustomerModal');
        }

        function confirmDelete(form, customerName) {
            const isDark = document.documentElement.classList.contains('dark');
            Swal.fire({
                title: 'ลบบัญชีลูกค้านี้?',
                html: `คุณแน่ใจหรือไม่ที่จะลบบัญชี <b>${customerName}</b> ?<br><span class="text-sm text-rose-500">ประวัติออเดอร์ของลูกค้าอาจหายไปด้วย</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'ใช่, ลบทิ้งเลย',
                cancelButtonText: 'ยกเลิก',
                background: isDark ? '#1e293b' : '#ffffff',
                color: isDark ? '#f8fafc' : '#1e293b',
                customClass: { popup: 'rounded-3xl' }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        }
    </script>
@endsection