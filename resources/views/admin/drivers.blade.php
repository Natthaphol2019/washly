@extends('layouts.admin')
@section('title', 'จัดการพนักงานขับรถ - Washly Admin')
@section('page_title', 'จัดการพนักงานขับรถ') {{-- 👈 ส่งชื่อไปแสดงบน Header --}}

@section('content')
<div class="space-y-6">

    {{-- หัวข้อและปุ่มเพิ่มคนขับ --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl washly-card shadow-sm flex items-center justify-center border border-sky-100 dark:border-slate-700">
                <i class="fa-solid fa-motorcycle text-2xl text-sky-500"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold washly-shell">พนักงานขับรถ</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">จัดการรายชื่อผู้ส่งผ้าทั้งหมดในระบบ</p>
            </div>
        </div>

        <button onclick="document.getElementById('addDriverModal').classList.remove('hidden')"
            class="washly-brand-btn px-5 py-2.5 rounded-xl text-sm font-semibold text-white shadow-lg shadow-sky-500/30 flex items-center gap-2 hover:brightness-110 transition">
            <i class="fa-solid fa-plus"></i> เพิ่มคนขับรถ
        </button>
    </div>

    {{-- 🔍 Filter และ Search --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-5">
        <form method="GET" action="{{ route('admin.drivers.index') }}" class="space-y-4">
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

                {{-- เรียงลำดับ --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5">
                        <i class="fa-solid fa-arrow-down-a-z"></i> เรียงตาม
                    </label>
                    <select name="sort" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none transition">
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>ชื่อ A-Z</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>ชื่อ Z-A</option>
                        <option value="created_at_desc" {{ request('sort') == 'created_at_desc' ? 'selected' : '' }}>ใหม่ → เก่า</option>
                        <option value="created_at_asc" {{ request('sort') == 'created_at_asc' ? 'selected' : '' }}>เก่า → ใหม่</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-wrap gap-3 pt-2">
                <button type="submit" class="bg-sky-500 hover:bg-sky-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-magnifying-glass"></i> ค้นหา
                </button>
                <a href="{{ route('admin.drivers.index') }}" class="bg-gray-100 hover:bg-gray-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-700 dark:text-gray-300 px-5 py-2.5 rounded-xl text-sm font-semibold transition flex items-center gap-2">
                    <i class="fa-solid fa-rotate-left"></i> ล้างตัวกรอง
                </a>
                
                @if(request()->hasAny(['search', 'sort']))
                    <div class="flex items-center gap-2 ml-auto text-xs text-gray-500 dark:text-gray-400">
                        <span class="font-semibold text-sky-600 dark:text-sky-400">{{ $drivers->count() }}</span> คนขับ
                    </div>
                @endif
            </div>
        </form>
    </div>

    {{-- สรุปผลการค้นหา --}}
    @if(request()->hasAny(['search']))
        <div class="flex items-center justify-between text-sm bg-sky-50 dark:bg-sky-900/20 border border-sky-100 dark:border-sky-800 px-4 py-2.5 rounded-xl">
            <p class="text-sky-700 dark:text-sky-400">
                <i class="fa-solid fa-filter"></i> 
                กำลังแสดงผลลัพธ์ที่กรอง: 
                @if(request('search')) <span class="font-semibold">ค้นหา="{{ request('search') }}"</span> @endif
            </p>
            <span class="text-sky-600 dark:text-sky-400 font-bold">{{ $drivers->count() }} คนขับ</span>
        </div>
    @endif

    {{-- ตารางรายชื่อคนขับ --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-600 dark:text-slate-300 border-b border-slate-200 dark:border-slate-700">
                    <tr>
                        <th class="px-6 py-4 font-semibold">ชื่อ-นามสกุล</th>
                        <th class="px-6 py-4 font-semibold">ชื่อผู้ใช้ (Username)</th>
                        <th class="px-6 py-4 font-semibold">เบอร์โทรศัพท์</th>
                        <th class="px-6 py-4 font-semibold text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50 washly-shell">
                    @forelse($drivers as $driver)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-sky-100 dark:bg-sky-900/40 text-sky-600 dark:text-sky-400 flex items-center justify-center font-bold">
                                        {{ mb_substr($driver->fullname, 0, 1) }}
                                    </div>
                                    <span class="font-medium">{{ $driver->fullname }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $driver->username }}</td>
                            <td class="px-6 py-4">{{ $driver->phone ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    {{-- ปุ่มแก้ไข --}}
                                    <button onclick="openEditModal({{ $driver }})" class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-500 hover:bg-amber-100 transition flex items-center justify-center border border-amber-200 dark:border-amber-800/50">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </button>
                                    
                                    {{-- ปุ่มลบ --}}
                                    <form action="{{ route('admin.drivers.destroy', $driver->id) }}" method="POST" class="m-0 inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete(this.closest('form'))" class="w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-900/30 text-rose-500 hover:bg-rose-100 transition flex items-center justify-center border border-rose-200 dark:border-rose-800/50">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">
                                <i class="fa-solid fa-motorcycle text-4xl mb-3 opacity-50"></i>
                                <p>ยังไม่มีพนักงานขับรถในระบบ</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ========================================== --}}
{{-- 🟢 Modal: เพิ่มคนขับรถ --}}
{{-- ========================================== --}}
<div id="addDriverModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/70 backdrop-blur-sm" onclick="document.getElementById('addDriverModal').classList.add('hidden')"></div>
        
        <div class="relative inline-block w-full max-w-md p-6 sm:p-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-700">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold washly-brand-text flex items-center gap-2"><i class="fa-solid fa-user-plus text-sky-500"></i>เพิ่มคนขับรถใหม่</h3>
                <button onclick="document.getElementById('addDriverModal').classList.add('hidden')" class="text-gray-400 hover:text-rose-500 transition-colors p-1">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <form action="{{ route('admin.drivers.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">ชื่อ-นามสกุล <span class="text-rose-500">*</span></label>
                    <input type="text" name="fullname" required placeholder="เช่น สมชาย สายซิ่ง" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">ชื่อผู้ใช้งาน (Username) <span class="text-rose-500">*</span></label>
                    <input type="text" name="username" required placeholder="ใช้สำหรับเข้าสู่ระบบ" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">เบอร์โทรศัพท์ <span class="text-rose-500">*</span></label>
                    <input type="text" name="phone" required placeholder="08X-XXX-XXXX" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">รหัสผ่าน <span class="text-rose-500">*</span></label>
                    <input type="password" name="password" required minlength="8" placeholder="อย่างน้อย 8 ตัวอักษร" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500 transition">
                </div>
                
                <div class="pt-2">
                    <button type="submit" class="w-full py-3 washly-brand-btn rounded-xl font-bold text-white shadow-md hover:brightness-110 transition">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ========================================== --}}
{{-- 🟡 Modal: แก้ไขคนขับรถ --}}
{{-- ========================================== --}}
<div id="editDriverModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/70 backdrop-blur-sm" onclick="document.getElementById('editDriverModal').classList.add('hidden')"></div>
        
        <div class="relative inline-block w-full max-w-md p-6 sm:p-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-700">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-amber-500 flex items-center gap-2"><i class="fa-solid fa-pen-to-square"></i>แก้ไขข้อมูล</h3>
                <button onclick="document.getElementById('editDriverModal').classList.add('hidden')" class="text-gray-400 hover:text-rose-500 transition-colors p-1">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <form id="editDriverForm" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">ชื่อ-นามสกุล</label>
                    <input type="text" name="fullname" id="edit_fullname" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">ชื่อผู้ใช้งาน</label>
                    <input type="text" name="username" id="edit_username" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">เบอร์โทรศัพท์</label>
                    <input type="text" name="phone" id="edit_phone" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition">
                </div>
                
                <div class="relative py-2">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="w-full border-t border-slate-200 dark:border-slate-700"></div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">รหัสผ่านใหม่ <span class="text-xs text-gray-400 font-normal ml-1">(ปล่อยว่างถ้าไม่เปลี่ยน)</span></label>
                    <input type="password" name="password" minlength="8" placeholder="••••••••" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition">
                </div>
                
                <div class="pt-2">
                    <button type="submit" class="w-full py-3 bg-amber-500 hover:bg-amber-600 transition rounded-xl font-bold text-white shadow-md hover:shadow-lg">บันทึกการเปลี่ยนแปลง</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEditModal(driver) {
        document.getElementById('edit_fullname').value = driver.fullname;
        document.getElementById('edit_username').value = driver.username;
        document.getElementById('edit_phone').value = driver.phone || '';
        document.getElementById('editDriverForm').action = `/admin/drivers/${driver.id}`;
        document.getElementById('editDriverModal').classList.remove('hidden');
    }

    function confirmDelete(form) {
        const isDark = document.documentElement.classList.contains('dark');
        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: "ข้อมูลคนขับรถคนนี้จะถูกลบออกจากระบบทันที",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'ใช่, ลบเลย!',
            cancelButtonText: 'ยกเลิก',
            background: isDark ? '#1e293b' : '#ffffff',
            color: isDark ? '#f8fafc' : '#1e293b',
            customClass: { popup: 'rounded-3xl' }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>
@endsection